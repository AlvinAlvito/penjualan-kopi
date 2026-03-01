<?php

namespace Tests\Feature;

use App\Services\RecommendationService;
use Database\Seeders\CoffeeProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_scenario_one_prioritizes_natural(): void
    {
        $this->seed(CoffeeProductSeeder::class);

        $result = app(RecommendationService::class)->recommend(null, 'segar fruity aftertaste lembut', 3);

        $this->assertSame('Natural', $result[0]['product_name']);
        $this->assertGreaterThanOrEqual(0, $result[0]['final_score']);
        $this->assertLessThanOrEqual(1, $result[0]['final_score']);
    }

    public function test_scenario_two_prioritizes_semi_wash(): void
    {
        $this->seed(CoffeeProductSeeder::class);

        $result = app(RecommendationService::class)->recommend(null, 'kopi segar manis floral', 3);

        $this->assertSame('Semi Wash', $result[0]['product_name']);
    }
}
