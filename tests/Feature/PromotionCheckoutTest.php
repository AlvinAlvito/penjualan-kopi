<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use App\Services\MidtransService;
use Database\Seeders\CoffeeProductSeeder;
use Database\Seeders\PromotionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PromotionCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_applies_valid_promotion_code(): void
    {
        $this->seed(CoffeeProductSeeder::class);
        $this->seed(PromotionSeeder::class);

        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $cart = Cart::query()->create(['user_id' => $user->id, 'status' => 'active']);
        CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'qty' => 1,
            'unit_price' => 100000,
            'subtotal' => 100000,
        ]);

        $this->instance(MidtransService::class, Mockery::mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('createTransaction')->once()->andReturn('snap-token-test');
        }));

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'address' => 'Alamat Test',
            'courier' => 'jne',
            'service' => 'reg',
            'shipping_cost' => 10000,
            'promo_code' => 'J2HEMAT10',
            'note' => 'test',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'discount_amount' => 10000,
            'total' => 100000,
        ]);

        $this->assertDatabaseHas('promotions', [
            'code' => 'J2HEMAT10',
            'used_count' => 1,
        ]);
    }
}
