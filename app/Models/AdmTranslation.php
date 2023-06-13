<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'model_type',
        'model_id',
        'lang',
        'hash',
    ];
}
