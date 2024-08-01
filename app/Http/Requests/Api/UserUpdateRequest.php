<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'max:255',
            // 'email' => ['required', 'email', Rule::unique('users')->ignore($this->user, 'uuid')->whereNull('deleted_at')],
            'password' => 'confirmed|min:8',
            'language' => 'in:pt,en',
            'phone' => 'nullable|max:255',
        ];

        return $rules;
    }
}
