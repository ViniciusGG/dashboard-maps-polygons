<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationData;

class AlertCreatedInternalRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'azulfy_internal_code' => 'required|max:255',
            'alerts' => 'required|array',
            'alerts.*.lat' => 'required|max:255',
            'alerts.*.lng' => 'required|max:255',
            'alerts.*.satellite_uuid' => 'uuid|required|exists:satellites,uuid',
            'alerts.*.intensity' => 'required|int',
            'alerts.*.area' => 'required|int',
            'alerts.*.severity' => 'required|int|min:1|max:4',
            'alerts.*.algorithm_source' => 'nullable|max:255',
            'alerts.*.alert_timestamp' => ['required', 'int', 'regex:/^\d{10}$/'],
            'alerts.*.indicator_uuid' => 'uuid|required|exists:indicators,uuid',
            'alerts.*.images' => 'required|array',
            'alerts.*.images.*.url' => 'required|max:255',
            'alerts.*.images.*.algorithm_type' => 'required|int',
            'alerts.*.images.*.geo_coordinates' => 'required|array',
            'alerts.*.images.*.geo_coordinates.tl' => ['required', 'array', 'size:2'],
            'alerts.*.images.*.geo_coordinates.tl.*' => 'required|numeric',
            'alerts.*.images.*.geo_coordinates.tr' => ['required', 'array', 'size:2'],
            'alerts.*.images.*.geo_coordinates.tr.*' => 'required|numeric',
            'alerts.*.images.*.geo_coordinates.br' => ['required', 'array', 'size:2'],
            'alerts.*.images.*.geo_coordinates.br.*' => 'required|numeric',
            'alerts.*.images.*.geo_coordinates.bl' => ['required', 'array', 'size:2'],
            'alerts.*.images.*.geo_coordinates.bl.*' => 'required|numeric',
        ];
    }
}
