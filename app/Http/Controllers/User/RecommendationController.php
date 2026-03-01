<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\InteractionService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly RecommendationService $recommendationService,
        private readonly InteractionService $interactionService,
    ) {}

    public function index(Request $request)
    {
        $query = trim((string) $request->query('query', ''));
        $result = [];

        if ($query !== '') {
            $userId = auth()->id();
            $result = $this->recommendationService->recommend($userId, $query, 3);

            if ($userId) {
                $this->interactionService->log($userId, 'search', null, $query);
            }
        }

        return view('user.recommendation', [
            'query' => $query,
            'result' => $result,
        ]);
    }
}
