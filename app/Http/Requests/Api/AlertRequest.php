<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $config = config('settings');
        if ($this->isMethod('POST')) {
            $roles = [
                'name' => 'required|string',
                'category' => ['required', Rule::in(array_keys(data_get($config, 'alerts.category')))],
                'indicator' => 'required|exists:indicators,id',
                'type' => ['required', Rule::in(array_keys(data_get($config, 'alerts.types')))],
                'status' => 'boolean',
                'lat' => 'required|string',
                'lng' => 'required|string',
                'alert_datetime' => 'required|date',
            ];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $roles = [
                'name' => 'string|between:3,255',
                'indicator' => 'exists:indicators,id',
                'category' => [Rule::in(array_keys(data_get($config, 'alerts.category')))],
                'type' => [Rule::in(array_keys(data_get($config, 'alerts.types')))],
                'status' => 'boolean',
                'lat' => 'string',
                'lng' => 'string',
                'alert_datetime' => 'date',
            ];
        }

        return $roles;
    }
}
