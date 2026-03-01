<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private readonly RecommendationService $recommendationService) {}

    public function index()
    {
        $products = Product::query()->with(['category', 'primaryImage'])->latest()->paginate(10);
        $categories = Category::query()->where('is_active', true)->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $product = Product::query()->create($data + ['slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(5))]);
        $this->storePrimaryImage($request, $product);
        $this->recommendationService->refreshTfIdfIndex();

        return back()->with('success', 'Produk ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $product->update($data);
        $this->storePrimaryImage($request, $product);
        $this->recommendationService->refreshTfIdfIndex();

        return back()->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->recommendationService->refreshTfIdfIndex();
        return back()->with('success', 'Produk dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight_gram' => ['required', 'integer', 'min:1'],
            'processing_method' => ['required', 'in:full_wash,semi_wash,natural,honey,wine'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]) + ['is_active' => (bool) $request->boolean('is_active')];
    }

    private function storePrimaryImage(Request $request, Product $product): void
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $path = $request->file('image')->store('products', 'public');
        $existingPrimary = $product->primaryImage()->first();

        if ($existingPrimary && $existingPrimary->image_path) {
            Storage::disk('public')->delete($existingPrimary->image_path);
            $existingPrimary->delete();
        }

        ProductImage::query()->where('product_id', $product->id)->update(['is_primary' => false]);

        ProductImage::query()->create([
            'product_id' => $product->id,
            'image_path' => $path,
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }
}
