<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Status extends Model
{
    use HasFactory;
    use Uuids;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'id',
        'pivot',
        'created_at',
        'updated_at',
    ];

    public $translatable = ['name'];
}
