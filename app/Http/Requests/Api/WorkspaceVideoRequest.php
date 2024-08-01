<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WorkspaceVideoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'workspace_id' => 'required',
            'name' => 'required',
            'url' => 'required',
            'region_map_area' => 'nullable|json',
        ];
    }
}
