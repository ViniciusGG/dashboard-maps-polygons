<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string',
            'members' => 'required|integer',
            'color'   => 'required|string',
            'services' => 'nullable|array',
            'services.*' => 'required|string|exists:services,uuid',
            'filters' => 'array',
            'filters.*' => 'required|string|exists:filters,uuid',
            'indicators' => 'nullable|array',
            'indicators.*' => 'required|string|exists:indicators,uuid',
            'admin_role' => 'nullable|array',
            'admin_role.*' => 'required|string|exists:permissions,uuid',
            'technicians_role' => 'nullable|array',
            'technicians_role.*' => 'required|string|exists:permissions,uuid',
            'external_service_provider_role' => 'nullable|array',
            'external_service_provider_role.*' => 'required|string|exists:permissions,uuid',
        ];
    }
}
