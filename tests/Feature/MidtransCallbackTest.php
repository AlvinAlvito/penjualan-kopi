<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CoffeeProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MidtransCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_callback_updates_order_to_paid(): void
    {
        Config::set('services.midtrans.server_key', 'server-key-test');
        $this->seed(CoffeeProductSeeder::class);

        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $order = Order::query()->create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-1',
            'status' => 'pending',
            'subtotal' => 100000,
            'shipping_cost' => 10000,
            'discount_amount' => 0,
            'total' => 110000,
            'shipping_address_json' => ['address' => 'A'],
            'ordered_at' => now(),
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'qty' => 1,
            'unit_price' => 100000,
            'subtotal' => 100000,
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'method' => 'midtrans',
            'provider' => 'midtrans',
            'transaction_status' => 'pending',
            'gross_amount' => 110000,
        ]);

        $payload = [
            'order_id' => 'INV-TEST-1',
            'status_code' => '200',
            'gross_amount' => '110000',
            'transaction_status' => 'settlement',
        ];

        $payload['signature_key'] = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].'server-key-test');

        $response = $this->postJson(route('payment.midtrans.callback'), $payload);

        $response->assertOk();
        $this->assertSame('paid', $order->fresh()->status);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        Config::set('services.midtrans.server_key', 'server-key-test');

        $response = $this->postJson(route('payment.midtrans.callback'), [
            'order_id' => 'INV-INVALID',
            'status_code' => '200',
            'gross_amount' => '100',
            'signature_key' => 'invalid',
        ]);

        $response->assertForbidden();
    }
}
