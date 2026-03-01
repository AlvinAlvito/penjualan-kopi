<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoffeeProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->firstOrCreate(
            ['slug' => 'kopi-arabika-gayo'],
            [
                'name' => 'Kopi Arabika Gayo',
                'description' => 'Varian kopi Arabika Gayo premium.',
                'is_active' => true,
            ]
        );

        $products = [
            [
                'name' => 'Full Wash',
                'description' => 'Kopi arabika gayo premium full wash rasa asam segar coklat ringan sensasi klasik jernih aroma floral lembut jeruk bunga aftertaste bersih.',
                'processing_method' => 'full_wash',
                'price' => 85000,
                'stock' => 100,
            ],
            [
                'name' => 'Semi Wash',
                'description' => 'Kopi arabika gayo premium semi wash rasa seimbang asam buah segar manis karamel lembut aroma floral tropis aftertaste tahan lama gurih legit.',
                'processing_method' => 'semi_wash',
                'price' => 90000,
                'stock' => 100,
            ],
            [
                'name' => 'Natural',
                'description' => 'Kopi arabika gayo premium natural rasa fruity manis buah ceri anggur aroma kuat aftertaste pedas halus penuh eksotis segar alami asam sedang rendah.',
                'processing_method' => 'natural',
                'price' => 95000,
                'stock' => 100,
            ],
            [
                'name' => 'Honey',
                'description' => 'Kopi arabika gayo premium honey rasa manis madu aroma vanilla rempah hangat aftertaste lembut tahan lama alami asam rendah halus unik seimbang.',
                'processing_method' => 'honey',
                'price' => 98000,
                'stock' => 100,
            ],
            [
                'name' => 'Wine',
                'description' => 'Kopi arabika gayo premium wine rasa kompleks fruity asam anggur fermentasi aroma kuat wangi aftertaste tajam panjang berbuah unik eksotis.',
                'processing_method' => 'wine',
                'price' => 105000,
                'stock' => 100,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $category->id,
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'weight_gram' => 250,
                    'processing_method' => $product['processing_method'],
                    'is_active' => true,
                ]
            );
        }

        app(RecommendationService::class)->refreshTfIdfIndex();
    }
}
