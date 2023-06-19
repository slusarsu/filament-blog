<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short',
        'content',
        'template',
        'custom_fields',
        'seo_title',
        'seo_text_keys',
        'seo_description',
        'is_enabled',
        'lang',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'custom_fields' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_enabled', true)
            ->where('created_at', '<=',Carbon::now());
    }

    public function customFields(): array
    {
        $fields = [];

        foreach($this->custom_fields as $field) {
            $fields[$field['data']['field_name']] = $field['data'];
        }

        return $fields;
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

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function translation(): HasOne
    {
        return $this->hasOne(AdmTranslation::class, 'model_id', 'id')->where('model_type', get_called_class());
    }

    public function translations(): \Illuminate\Database\Eloquent\Collection|array
    {
        $translation = $this->translation()->first();

        if(!$translation) {
            return [];
        }

        return AdmTranslation::query()->where('hash', $translation->hash)->get();
    }
}
