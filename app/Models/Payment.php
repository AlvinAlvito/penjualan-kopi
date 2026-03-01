<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'provider',
        'provider_ref',
        'transaction_status',
        'gross_amount',
        'paid_at',
        'payload_json',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
