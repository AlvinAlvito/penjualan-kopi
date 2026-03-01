<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::query()
            ->with('product.primaryImage')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('user.wishlist.index', compact('items'));
    }

    public function store(Product $product)
    {
        Wishlist::query()->firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy(Wishlist $wishlist)
    {
        abort_unless($wishlist->user_id === auth()->id(), 403);
        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
