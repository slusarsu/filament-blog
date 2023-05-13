<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'body',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];
}
