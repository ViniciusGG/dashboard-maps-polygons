<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DangerousAlertsManagersRequest extends FormRequest
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
            'dangerous_alerts_managers_ids' => ['nullable', 'array'],
            'dangerous_alerts_managers_ids.*' => ['exists:users,uuid'],
        ];
    }

    public function messages()
    {
        return [
            'dangerous_alerts_managers_ids.*.exists' => 'user :input not found.',
        ];
    }
}
