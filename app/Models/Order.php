<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_no',
        'status',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total',
        'promotion_id',
        'shipping_province_id',
        'shipping_province_name',
        'shipping_city_id',
        'shipping_city_name',
        'shipping_district_id',
        'shipping_district_name',
        'shipping_address_json',
        'note',
        'ordered_at',
    ];

    protected $casts = [
        'shipping_address_json' => 'array',
        'ordered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
