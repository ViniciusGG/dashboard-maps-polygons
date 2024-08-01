<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WorkspaceManagerRequest extends FormRequest
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
            'alerts_managers_ids' => ['nullable', 'array'],
            'alerts_managers_ids.*' => ['exists:users,uuid'],
        ];
    }

    public function messages()
    {
        return [
            'alerts_managers_ids.*.exists' => 'user :input not found.',
        ];
    }
}
