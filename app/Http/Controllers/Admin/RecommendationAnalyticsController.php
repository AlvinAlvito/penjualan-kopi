<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecommendationLog;
use App\Services\RecommendationService;
use Illuminate\Support\Facades\DB;

class RecommendationAnalyticsController extends Controller
{
    public function __construct(private readonly RecommendationService $recommendationService) {}

    public function index()
    {
        $scenarioSet = [
            ['query' => 'segar fruity aftertaste lembut', 'relevant' => ['Natural', 'Wine', 'Full Wash']],
            ['query' => 'kopi segar manis floral', 'relevant' => ['Semi Wash', 'Full Wash', 'Natural']],
        ];

        $evaluation = $this->recommendationService->evaluate($scenarioSet);
        $recentLogs = RecommendationLog::query()->latest()->limit(20)->get();

        $topQueries = RecommendationLog::query()
            ->select('query_text', DB::raw('COUNT(*) as total'))
            ->whereNotNull('query_text')
            ->groupBy('query_text')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.recommendation-analytics', compact('evaluation', 'recentLogs', 'topQueries'));
    }
}
