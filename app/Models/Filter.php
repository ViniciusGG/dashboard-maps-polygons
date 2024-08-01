<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'Filters ';
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'filter_id');
    }
}
