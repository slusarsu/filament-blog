<?php

namespace App\Models;

use App\Adm\Traits\ModelHasAdmTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    use ModelHasAdmTranslation;


    protected $fillable = [
        'title',
        'slug',
        'is_enabled',
        'lang',
        'position',
    ];
    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function menu_items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
