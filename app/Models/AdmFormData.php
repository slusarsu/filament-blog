<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmFormData extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'is_enabled',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array'
    ];
}
