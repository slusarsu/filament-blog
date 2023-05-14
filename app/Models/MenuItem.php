<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'order',
        'is_enabled',
        'menu_id',
        'parent_id',
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public static function tree()
    {
        $allItems = MenuItem::all();

        $rootItems = $allItems->whereNull('parent_id');

        self::formatTree($rootItems, $allItems);

        return $rootItems;
    }

    private static function formatTree($rootItems, $allItems): void
    {
        foreach ($rootItems as $item) {
            $item->sub_item = $allItems->where('parent_id', $item->id)->values();

            if ($item->sub_item->isNotEmpty()) {
                self::formatTree($item->sub_item, $allItems);
            }
        }
    }
}
