<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductTerm;
use App\Models\RecommendationLog;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    public function __construct(private readonly TextPreprocessingService $preprocessing) {}

    public function refreshTfIdfIndex(): void
    {
        $products = Product::query()->where('is_active', true)->get();
        if ($products->isEmpty()) {
            ProductTerm::query()->delete();
            return;
        }

        $docTerms = [];
        $df = [];

        foreach ($products as $product) {
            $terms = $this->preprocessing->preprocess($product->name . ' ' . $product->description . ' ' . $product->processing_method);
            $counts = array_count_values($terms);
            $docTerms[$product->id] = [
                'total' => max(count($terms), 1),
                'counts' => $counts,
            ];

            foreach (array_keys($counts) as $term) {
                $df[$term] = ($df[$term] ?? 0) + 1;
            }
        }

        $n = $products->count();

        DB::transaction(function () use ($docTerms, $df, $n) {
            ProductTerm::query()->delete();
            $rows = [];

            foreach ($docTerms as $productId => $meta) {
                foreach ($meta['counts'] as $term => $count) {
                    $tf = $count / $meta['total'];
                    $idf = $df[$term] > 0 ? log10($n / $df[$term]) : 0;
                    $rows[] = [
                        'product_id' => $productId,
                        'term' => $term,
                        'tf' => $tf,
                        'idf' => $idf,
                        'tf_idf' => $tf * $idf,
                        'updated_at' => now(),
                    ];
                }
            }

            foreach (array_chunk($rows, 500) as $chunk) {
                ProductTerm::query()->insert($chunk);
            }
        });
    }

    public function recommend(?int $userId, string $query, int $topN = 3): array
    {
        $queryTerms = $this->preprocessing->preprocess($query);
        $queryVector = $this->buildQueryVector($queryTerms);
        $profileVector = $this->buildProfileVector($userId);

        $results = [];
        $products = Product::query()->with(['terms', 'primaryImage'])->where('is_active', true)->get();

        foreach ($products as $product) {
            $productVector = $product->terms->pluck('tf_idf', 'term')->toArray();
            $simQuery = $this->cosineSimilarity($queryVector, $productVector);
            $simProfile = $this->cosineSimilarity($profileVector, $productVector);
            $final = (0.7 * $simQuery) + (0.3 * $simProfile);

            $results[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'image_url' => $product->image_url,
                'similarity_query' => round($simQuery, 4),
                'similarity_profile' => round($simProfile, 4),
                'final_score' => round($final, 4),
            ];
        }

        usort($results, fn ($a, $b) => $b['final_score'] <=> $a['final_score']);
        $ranked = array_map(function ($row, $index) {
            $row['rank'] = $index + 1;
            return $row;
        }, array_slice($results, 0, $topN), array_keys(array_slice($results, 0, $topN)));

        RecommendationLog::query()->create([
            'user_id' => $userId,
            'query_text' => $query,
            'result_json' => $ranked,
            'top_n' => $topN,
        ]);

        return $ranked;
    }

    public function evaluate(array $scenarioSet): array
    {
        $scores = [];
        foreach ($scenarioSet as $scenario) {
            $result = $this->recommend(null, $scenario['query'], 3);
            $predicted = collect($result)->pluck('product_name')->map(fn ($n) => strtolower($n))->all();
            $actual = collect($scenario['relevant'])->map(fn ($n) => strtolower($n))->all();

            $tp = count(array_intersect($predicted, $actual));
            $fp = count(array_diff($predicted, $actual));
            $fn = count(array_diff($actual, $predicted));

            $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
            $f1 = ($precision + $recall) > 0 ? 2 * (($precision * $recall) / ($precision + $recall)) : 0;

            $scores[] = [
                'query' => $scenario['query'],
                'tp' => $tp,
                'fp' => $fp,
                'fn' => $fn,
                'precision' => round($precision, 2),
                'recall' => round($recall, 2),
                'f1_score' => round($f1, 2),
            ];
        }

        return $scores;
    }

    private function buildQueryVector(array $terms): array
    {
        if (empty($terms)) {
            return [];
        }

        $counts = array_count_values($terms);
        $total = count($terms);
        $idfMap = ProductTerm::query()
            ->whereIn('term', array_keys($counts))
            ->select('term', DB::raw('MAX(idf) as idf'))
            ->groupBy('term')
            ->pluck('idf', 'term')
            ->toArray();

        $vector = [];
        foreach ($counts as $term => $count) {
            $tf = $count / $total;
            $idf = $idfMap[$term] ?? 0;
            $vector[$term] = $tf * $idf;
        }

        return $vector;
    }

    private function buildProfileVector(?int $userId): array
    {
        if (!$userId) {
            return [];
        }

        $interactions = UserInteraction::query()
            ->with('product.terms')
            ->where('user_id', $userId)
            ->latest()
            ->limit(100)
            ->get();

        $vector = [];
        foreach ($interactions as $interaction) {
            if (!$interaction->product) {
                continue;
            }

            foreach ($interaction->product->terms as $term) {
                $vector[$term->term] = ($vector[$term->term] ?? 0) + ($term->tf_idf * $interaction->weight);
            }
        }

        return $vector;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $dot = 0;
        foreach ($a as $term => $weight) {
            $dot += $weight * ($b[$term] ?? 0);
        }

        $normA = sqrt(array_sum(array_map(fn ($v) => $v * $v, $a)));
        $normB = sqrt(array_sum(array_map(fn ($v) => $v * $v, $b)));

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $dot / ($normA * $normB);
    }
}
