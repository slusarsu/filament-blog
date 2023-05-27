<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'is_enabled',
        'lang',
    ];
    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function menu_items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
