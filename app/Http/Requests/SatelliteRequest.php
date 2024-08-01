<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SatelliteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string',
                'description_en' => 'required|string',
                'description_pt' => 'required|string',
            ];
        }else{
        return [
            'name' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'description_pt' => 'sometimes|string',
            ];
        }
    }
}
