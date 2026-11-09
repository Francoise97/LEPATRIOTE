<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakingNews extends Model
{
    protected $fillable = [
        'text',
        'url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
