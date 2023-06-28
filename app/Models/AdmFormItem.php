<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmFormItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'adm_form_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function admForm()
    {
        return $this->belongsTo(AdmForm::class);
    }
}
