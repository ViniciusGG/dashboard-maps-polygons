<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satellite extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public static function getModelLabel()
    {
        return 'Satellite ';
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

}
