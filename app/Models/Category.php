<?php

namespace App\Models;

use App\Adm\Traits\ModelHasAdmTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use ModelHasAdmTranslation;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'post_type',
        'content',
        'seo_title',
        'seo_text_keys',
        'seo_description',
        'is_enabled',
        'lang',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public static function getAllWithTypes()
    {
        $categories = Category::get();
        $categoryMapped = $categories->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item['title'].' (type: '.$item['post_type'].')'];
        });
        return $categoryMapped->all();

    }

    public static function tree()
    {
        $allCategories = Category::all();

        $rootCategories = $allCategories->whereNull('parent_id');

        self::formatTree($rootCategories, $allCategories);

        return $rootCategories;
    }

    private static function formatTree($rootCategories, $allCategories): void
    {
        foreach ($rootCategories as $category) {
            $category->sub_cat = $allCategories->where('parent_id', $category->id)->values();

            if ($category->sub_cat->isNotEmpty()) {
                self::formatTree($category->sub_cat, $allCategories);
            }
        }
    }
}
