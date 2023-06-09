<?php

namespace App\Models;

use App\Adm\Traits\ModelHasAdmTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method active
 */
class Post extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use ModelHasAdmTranslation;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'short',
        'parent_id',
        'content',
        'seo_title',
        'seo_text_keys',
        'seo_description',
        'post_type',
        'is_enabled',
        'lang',
        'translation_code',
        'created_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'categories' => 'array',
        'tags' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->where('post_type', $this->type);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getTags()
    {
        return $this->tags()->get();
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_enabled', true)
        ->where('created_at', '<=',Carbon::now());
    }

    public function images(): array
    {
        $media = $this->getMedia('images');

        $images = [];

        foreach ($media as $image) {
            $images[] = $image->getUrl();
        }

        return $images;
    }

    public function thumb(): string
    {
        return $this->getFirstMediaUrl('thumbs');
    }

    public function url(): string
    {
        return url(app()->getLocale().'/post/'.$this->slug);
    }
}
