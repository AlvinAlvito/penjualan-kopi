<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::query()->latest()->paginate(12);
        $products = Product::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.promotions.index', compact('promotions', 'products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $promotion = Promotion::query()->create($data);
        $promotion->products()->sync($request->input('product_ids', []));

        return back()->with('success', 'Promo berhasil dibuat.');
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $this->validateData($request, $promotion->id);
        $promotion->update($data);
        $promotion->products()->sync($request->input('product_ids', []));

        return back()->with('success', 'Promo diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Promo dihapus.');
    }

    private function validateData(Request $request, ?int $promotionId = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('promotions', 'code')->ignore($promotionId)],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'integer', 'min:1'],
            'quota' => ['required', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
