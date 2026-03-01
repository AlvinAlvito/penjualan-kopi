<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\RecommendationService;
use App\Services\TextPreprocessingService;

class JournalValidationController extends Controller
{
    public function __construct(
        private readonly TextPreprocessingService $preprocessingService,
        private readonly RecommendationService $recommendationService,
    ) {}

    public function index()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'processing_method']);

        $documents = [];
        foreach ($products as $product) {
            $terms = $this->preprocessingService->preprocess(
                $product->name.' '.$product->description.' '.$product->processing_method
            );

            $documents[] = [
                'product' => $product,
                'terms' => $terms,
                'term_string' => implode(', ', $terms),
                'total_terms' => max(count($terms), 1),
            ];
        }

        $scenarioOne = $this->buildScenarioTables(
            $documents,
            'segar fruity aftertaste lembut',
            ['segar', 'fruity', 'aftertaste', 'lembut']
        );

        $scenarioTwo = $this->buildScenarioTables(
            $documents,
            'kopi segar manis floral',
            ['kopi', 'segar', 'manis', 'floral']
        );

        $journalTargets = [
            's1' => [
                'Natural' => 0.9284,
                'Wine' => 0.8508,
                'Full Wash' => 0.5256,
                'Semi Wash' => 0.5256,
                'Honey' => 0.3716,
            ],
            's2' => [
                'Semi Wash' => 1.0000,
                'Full Wash' => 0.9284,
                'Natural' => 0.5256,
                'Honey' => 0.3716,
                'Wine' => 0.0000,
            ],
        ];

        $webRankS1 = $this->recommendationService->recommend(null, 'segar fruity aftertaste lembut', 3);
        $webRankS2 = $this->recommendationService->recommend(null, 'kopi segar manis floral', 3);

        $journalRankS1 = [
            ['rank' => 1, 'product_name' => 'Natural', 'final_score' => 0.9284],
            ['rank' => 2, 'product_name' => 'Wine', 'final_score' => 0.8508],
            ['rank' => 3, 'product_name' => 'Full Wash', 'final_score' => 0.5256],
        ];
        $journalRankS2 = [
            ['rank' => 1, 'product_name' => 'Semi Wash', 'final_score' => 1.0000],
            ['rank' => 2, 'product_name' => 'Full Wash', 'final_score' => 0.9284],
            ['rank' => 3, 'product_name' => 'Natural', 'final_score' => 0.5256],
        ];

        $journalMetric = [
            'precision' => 0.66,
            'recall' => 1.00,
            'f1_score' => 0.79,
        ];

        return view('admin.journal-validation', [
            'products' => $products,
            'documents' => $documents,
            'scenarioOne' => $scenarioOne,
            'scenarioTwo' => $scenarioTwo,
            'journalTargets' => $journalTargets,
            'journalRankS1' => $journalRankS1,
            'journalRankS2' => $journalRankS2,
            'webRankS1' => $webRankS1,
            'webRankS2' => $webRankS2,
            'journalMetric' => $journalMetric,
        ]);
    }

    private function buildScenarioTables(array $documents, string $query, array $queryTerms): array
    {
        $queryTerms = array_values(array_map('strtolower', $queryTerms));
        $queryTermCount = max(count($queryTerms), 1);

        $rows = [];
        $df = array_fill_keys($queryTerms, 0);

        foreach ($documents as $doc) {
            $counts = array_count_values($doc['terms']);
            $row = [
                'product_name' => $doc['product']->name,
                'total_terms' => $doc['total_terms'],
                'tf_numerator' => [],
                'tf' => [],
                'tf_idf' => [],
            ];

            foreach ($queryTerms as $term) {
                $count = (int) ($counts[$term] ?? 0);
                $row['tf_numerator'][$term] = $count;
                $row['tf'][$term] = $count / $doc['total_terms'];
                if ($count > 0) {
                    $df[$term] += 1;
                }
            }

            $rows[] = $row;
        }

        $totalDocs = max(count($rows), 1);
        $idf = [];
        foreach ($queryTerms as $term) {
            $idf[$term] = ($df[$term] ?? 0) > 0 ? log10($totalDocs / $df[$term]) : 0.0;
        }

        $queryVector = [];
        foreach ($queryTerms as $term) {
            $tfQuery = 1 / $queryTermCount;
            $queryVector[$term] = $tfQuery * $idf[$term];
        }

        foreach ($rows as &$row) {
            $productVector = [];
            foreach ($queryTerms as $term) {
                $row['tf_idf'][$term] = $row['tf'][$term] * $idf[$term];
                $productVector[$term] = $row['tf_idf'][$term];
            }

            $row['similarity'] = $this->cosineSimilarity($queryVector, $productVector);
        }
        unset($row);

        usort($rows, fn (array $a, array $b) => $b['similarity'] <=> $a['similarity']);
        foreach ($rows as $index => &$row) {
            $row['rank'] = $index + 1;
        }
        unset($row);

        return [
            'query' => $query,
            'terms' => $queryTerms,
            'df' => $df,
            'idf' => $idf,
            'query_vector' => $queryVector,
            'rows' => $rows,
            'top3' => array_slice($rows, 0, 3),
        ];
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        foreach ($a as $term => $weight) {
            $dot += $weight * ($b[$term] ?? 0.0);
        }

        $normA = sqrt(array_sum(array_map(fn ($v) => $v * $v, $a)));
        $normB = sqrt(array_sum(array_map(fn ($v) => $v * $v, $b)));

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $dot / ($normA * $normB);
    }
}

