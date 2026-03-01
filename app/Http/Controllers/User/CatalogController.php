<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\InteractionService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(
        private readonly RecommendationService $recommendationService,
        private readonly InteractionService $interactionService,
    ) {}

    public function home()
    {
        $products = Product::query()->with('primaryImage')->where('is_active', true)->latest()->take(8)->get();
        $recommendations = auth()->check()
            ? $this->recommendationService->recommend(auth()->id(), 'kopi gayo segar manis floral', 3)
            : [];

        return view('user.home', compact('products', 'recommendations'));
    }

    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'primaryImage'])->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        if ($request->filled('q')) {
            $keyword = (string) $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });

            if (auth()->check()) {
                $this->interactionService->log(auth()->id(), 'search', null, $keyword);
            }
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::query()->where('is_active', true)->get();

        return view('user.catalog', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        if (auth()->check()) {
            $this->interactionService->log(auth()->id(), 'view', $product->id);
        }

        $product->load([
            'primaryImage',
            'reviews' => fn ($q) => $q->where('is_published', true)->with('user')->latest(),
        ]);

        return view('user.product-detail', compact('product'));
    }
}
