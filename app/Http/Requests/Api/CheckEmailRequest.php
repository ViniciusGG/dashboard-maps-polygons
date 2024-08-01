<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckEmailRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'email'],
        ];

        return $rules;
    }
}
