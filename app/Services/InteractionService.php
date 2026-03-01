<?php

namespace App\Services;

use App\Models\UserInteraction;

class InteractionService
{
    private const WEIGHTS = [
        'view' => 1,
        'search' => 1,
        'cart' => 2,
        'checkout' => 3,
        'purchase' => 4,
    ];

    public function log(int $userId, string $type, ?int $productId = null, ?string $queryText = null): void
    {
        if (!isset(self::WEIGHTS[$type])) {
            return;
        }

        UserInteraction::query()->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'interaction_type' => $type,
            'query_text' => $queryText,
            'weight' => self::WEIGHTS[$type],
        ]);
    }
}
