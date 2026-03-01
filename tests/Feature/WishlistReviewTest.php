<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Database\Seeders\CoffeeProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_and_remove_wishlist(): void
    {
        $this->seed(CoffeeProductSeeder::class);

        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $this->actingAs($user)->post(route('wishlist.store', $product->slug))->assertRedirect();
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);

        $wishlist = Wishlist::query()->where('user_id', $user->id)->where('product_id', $product->id)->firstOrFail();
        $this->actingAs($user)->delete(route('wishlist.destroy', $wishlist))->assertRedirect();

        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }

    public function test_review_moderation_flow(): void
    {
        $this->seed(CoffeeProductSeeder::class);

        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::query()->firstOrFail();

        $this->actingAs($user)->post(route('reviews.store', $product->slug), [
            'rating' => 5,
            'review_text' => 'Mantap sekali',
        ])->assertRedirect();

        $review = Review::query()->where('user_id', $user->id)->where('product_id', $product->id)->firstOrFail();
        $this->assertFalse($review->is_published);

        $this->actingAs($admin)->patch(route('admin.reviews.toggle', $review))->assertRedirect();
        $this->assertTrue($review->fresh()->is_published);
    }
}
