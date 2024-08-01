<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseFilter extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'license_id',
        'filter_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function license()
    {
        return $this->belongsTo(Licenses::class);
    }

    public static function getModelLabel()
    {
        return 'License Filter ';
    }
}