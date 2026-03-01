<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promo = Promotion::query()->updateOrCreate(
            ['code' => 'J2HEMAT10'],
            [
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'quota' => 100,
                'used_count' => 0,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        $products = Product::query()->pluck('id')->all();
        $promo->products()->sync($products);
    }
}
