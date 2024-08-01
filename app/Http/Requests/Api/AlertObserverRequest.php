<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AlertObserverRequest extends FormRequest
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
        return [
            'add' => ['nullable', 'array'],
            'add.*' => ['uuid', 'exists:users,uuid'],
            'remove' => ['nullable', 'array'],
            'remove.*' => ['uuid', 'exists:users,uuid'],
        ];
    }

    public function messages()
    {
        return [
            'add.*.uuid' => 'The :attribute must be a valid UUID',
            'add.*.exists' => 'The :attribute does not exist',
            'remove.*.uuid' => 'The :attribute must be a valid UUID',
            'remove.*.exists' => 'The :attribute does not exist',
        ];

    }
}
