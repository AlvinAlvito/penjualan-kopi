<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight_gram',
        'processing_method',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function terms()
    {
        return $this->hasMany(ProductTerm::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product');
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->relationLoaded('primaryImage') ? $this->primaryImage : $this->primaryImage()->first();
        if ($primary && $primary->image_path) {
            return Storage::url($primary->image_path);
        }

        $first = $this->relationLoaded('images') ? $this->images->first() : $this->images()->first();
        if ($first && $first->image_path) {
            return Storage::url($first->image_path);
        }

        return asset('Images/logo.png');
    }
}
