<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndicatorHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'page' => 'nullable|integer',
            'take' => 'nullable|integer',
            'closed' => 'nullable|integer',
            'where' => 'required|exists:filters,id',
            'what' => 'required|exists:indicators,id',
            'lt' => ['required', 'int', 'regex:/^\d{10}$/'],
            'gt' => ['required', 'int', 'regex:/^\d{10}$/'],
        ];
    }
}
