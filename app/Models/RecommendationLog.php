<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationLog extends Model
{
    protected $fillable = ['user_id', 'query_text', 'result_json', 'top_n'];

    protected $casts = [
        'result_json' => 'array',
    ];
}
