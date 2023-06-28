<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'is_enabled',
        'fields',
        'link_hash',
    ];

    protected $casts = [
        'fields' => 'array'
    ];

    public function admFormItems(): HasMany
    {
        return $this->hasMany(AdmFormItem::class, 'adm_form_id', 'id');
    }

    public function fields(): array
    {
        $fields = [];
        foreach($this->fields as $field) {
            $fields[] = $field['field_name'];
        }

        return $fields;
    }
}
