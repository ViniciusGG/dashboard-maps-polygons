<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LicenseService extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'license_id',
        'service_id',
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
        return 'License Service ';
    }
}
