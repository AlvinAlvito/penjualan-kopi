<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Support\Collection;

class PromotionService
{
    public function findValidByCode(string $code): ?Promotion
    {
        $promotion = Promotion::query()->whereRaw('LOWER(code) = ?', [strtolower($code)])->first();

        if (!$promotion || !$promotion->isValidNow()) {
            return null;
        }

        return $promotion;
    }

    public function calculateDiscount(Promotion $promotion, int $subtotal, Collection $cartItems): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $eligibleSubtotal = $subtotal;
        if ($promotion->products()->exists()) {
            $eligibleIds = $promotion->products()->pluck('products.id')->all();
            $eligibleSubtotal = $cartItems
                ->whereIn('product_id', $eligibleIds)
                ->sum('subtotal');
        }

        if ($eligibleSubtotal <= 0) {
            return 0;
        }

        $discount = $promotion->discount_type === 'percentage'
            ? (int) round($eligibleSubtotal * ($promotion->discount_value / 100))
            : min($promotion->discount_value, $eligibleSubtotal);

        return min($discount, $subtotal);
    }
}
