<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\InteractionService;
use App\Services\MidtransService;
use App\Services\NotificationService;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService,
        private readonly InteractionService $interactionService,
        private readonly PromotionService $promotionService,
        private readonly NotificationService $notificationService,
    ) {}

    public function show(Request $request)
    {
        $cart = $this->activeCart();
        $cart->load('items.product');

        $promoCode = trim((string) $request->query('promo_code', ''));
        $promotion = $promoCode !== '' ? $this->promotionService->findValidByCode($promoCode) : null;
        $subtotal = (int) $cart->items->sum('subtotal');
        $discount = $promotion ? $this->promotionService->calculateDiscount($promotion, $subtotal, $cart->items) : 0;

        return view('user.checkout', compact('cart', 'promotion', 'discount', 'promoCode'));
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'address' => ['required', 'string'],
            'courier' => ['required', 'string', 'max:50'],
            'service' => ['required', 'string', 'max:50'],
            'shipping_cost' => ['required', 'integer', 'min:0'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
        ]);

        $cart = $this->activeCart();
        $cart->load('items.product');
        abort_if($cart->items->isEmpty(), 422, 'Keranjang kosong.');

        $subtotal = (int) $cart->items->sum('subtotal');
        $promotion = null;
        $discount = 0;

        if (!empty($data['promo_code'])) {
            $promotion = $this->promotionService->findValidByCode($data['promo_code']);
            if (!$promotion) {
                return back()->withErrors(['promo_code' => 'Kode promo tidak valid atau sudah tidak aktif.'])->withInput();
            }
            $discount = $this->promotionService->calculateDiscount($promotion, $subtotal, $cart->items);
        }

        $total = $subtotal + (int) $data['shipping_cost'] - $discount;

        $order = DB::transaction(function () use ($cart, $data, $subtotal, $total, $discount, $promotion) {
            $order = Order::query()->create([
                'user_id' => auth()->id(),
                'invoice_no' => 'INV-' . now()->format('YmdHis') . '-' . auth()->id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => (int) $data['shipping_cost'],
                'discount_amount' => $discount,
                'total' => max($total, 0),
                'promotion_id' => $promotion?->id,
                'shipping_address_json' => [
                    'address' => $data['address'],
                    'courier' => $data['courier'],
                    'service' => $data['service'],
                ],
                'note' => $data['note'] ?? null,
                'ordered_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ]);
                $this->interactionService->log(auth()->id(), 'checkout', $item->product_id);
            }

            $payment = Payment::query()->create([
                'order_id' => $order->id,
                'method' => 'midtrans',
                'provider' => 'midtrans',
                'transaction_status' => 'pending',
                'gross_amount' => $order->total,
            ]);

            $cart->status = 'ordered';
            $cart->save();
            $cart->items()->delete();

            if ($promotion) {
                $promotion->increment('used_count');
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->invoice_no,
                    'gross_amount' => $order->total,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone,
                ],
            ];

            $payment->provider_ref = $this->midtransService->createTransaction($params);
            $payment->save();

            $this->notificationService->sendToUser(
                auth()->id(),
                'Pesanan dibuat',
                "Pesanan {$order->invoice_no} berhasil dibuat, silakan selesaikan pembayaran.",
                'order'
            );

            return $order;
        });

        return redirect()->route('orders.show', $order->invoice_no)->with('success', 'Pesanan berhasil dibuat.');
    }

    private function activeCart(): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id' => auth()->id(),
            'status' => 'active',
        ]);
    }
}
