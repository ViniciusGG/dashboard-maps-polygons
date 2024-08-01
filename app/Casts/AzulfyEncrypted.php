<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AzulfyEncrypted implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = base64_decode($value, true);

        return str_replace(config('app.key'), '', $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = config('app.key') . $value;

        return base64_encode($value);
    }
}
