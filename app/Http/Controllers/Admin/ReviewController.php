<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::query()->with(['user', 'product.primaryImage'])->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function togglePublish(Review $review)
    {
        $review->is_published = !$review->is_published;
        $review->save();

        return back()->with('success', 'Status ulasan diperbarui.');
    }
}
