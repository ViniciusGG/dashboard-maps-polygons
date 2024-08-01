<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
    public function rules(): array
    {

        $rules = [
            'description' => 'required|string',
            'files' => 'array|max:7',
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,mov,mp4,mkv,webm|max:100000',
        ];


        return $rules;
    }
}
