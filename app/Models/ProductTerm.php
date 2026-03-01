<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTerm extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'term', 'tf', 'idf', 'tf_idf', 'updated_at'];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
