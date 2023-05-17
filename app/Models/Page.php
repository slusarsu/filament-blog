<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'seo_text_keys',
        'seo_description',
        'is_enabled',
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
}
