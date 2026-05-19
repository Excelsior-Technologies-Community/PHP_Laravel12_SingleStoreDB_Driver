<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'meta',
        'status'
    ];

    protected $casts = [
        'meta' => 'array',
        'status' => 'boolean'
    ];
}