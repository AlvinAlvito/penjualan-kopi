<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\InteractionService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly InteractionService $interactionService) {}

    public function index()
    {
        $cart = $this->getActiveCart();
        $cart->load('items.product');
        return view('user.cart', compact('cart'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        abort_if($product->stock < 1, 422, 'Stok tidak tersedia.');

        $qty = $data['qty'] ?? 1;
        $cart = $this->getActiveCart();

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->qty = ($item->exists ? $item->qty : 0) + $qty;
        $item->unit_price = $product->price;
        $item->subtotal = $item->qty * $product->price;
        $item->save();

        $this->interactionService->log(auth()->id(), 'cart', $product->id);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $item)
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);

        $qty = (int) $request->validate(['qty' => ['required', 'integer', 'min:1']])['qty'];
        $item->qty = $qty;
        $item->subtotal = $qty * $item->unit_price;
        $item->save();

        return redirect()->route('cart.index');
    }

    public function destroy(CartItem $item)
    {
        abort_unless($item->cart->user_id === auth()->id(), 403);
        $item->delete();

        return redirect()->route('cart.index');
    }

    private function getActiveCart(): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id' => auth()->id(),
            'status' => 'active',
        ]);
    }
}
