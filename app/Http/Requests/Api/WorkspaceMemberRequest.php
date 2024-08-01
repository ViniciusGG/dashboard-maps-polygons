<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkspaceMemberRequest extends FormRequest
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
            'member_email' => 'required|email',
            'member_name' => 'required|string',
            'member_phone' => 'nullable|string',
            'role' => ['required', Rule::in(config('settings.roles.2'), config('settings.roles.3'), config('settings.roles.4'))],
        ];
    }
}
