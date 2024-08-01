<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueWorkspaceMemberByEmail;

class WorkspaceRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            $roles = [
                'name' => 'required',
                'license_id' => 'required|exists:licenses,uuid',
                'admin_email' => [
                    'required',
                    'email',
                    new UniqueWorkspaceMemberByEmail($this->input('admin_email')),
                ],
                'admin_name' => 'required',
                'admin_phone' => 'nullable|string',
                'region_map_area' => 'nullable|json',
                'region_map_area_points' => 'nullable|array',
                'region_map_area_points.*.name' => 'nullable',
                'region_map_area_points.*.json' => 'nullable|json',
                'code_azulfy' => 'nullable',
                'members' => 'nullable|array',
                'members.*.email' => [
                    'required',
                    'email',
                    new UniqueWorkspaceMemberByEmail($this->input('members.*.email')),
                ],
                'members.*.name' => 'nullable',
                'members.*.phone' => 'nullable|string',
                'members.*.role' => ['nullable', Rule::in(config('settings.roles.1'), config('settings.roles.2'), config('settings.roles.3'), config('settings.roles.4'))],
            ];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $roles = [
                'name' => 'nullable',
                'license_id' => 'nullable|exists:licenses,uuid',
                'admin_email' => [
                    'nullable',
                    'email',
                    new UniqueWorkspaceMemberByEmail($this->input('admin_email'))],
                'admin_name' => 'nullable',
                'admin_phone' => 'nullable|string',
                'region_map_area' => 'nullable|json',
                'region_map_area_points' => 'nullable|array',
                'region_map_area_points.*.name' => 'nullable',
                'region_map_area_points.*.json' => 'nullable|json',
                'code_azulfy' => 'nullable',
                'status' => 'nullable|in:approved,pending,rejected',
                'members' => 'nullable|array',
                'members.*.email' => [
                    'nullable',
                    'email',
                    new UniqueWorkspaceMemberByEmail($this->input('members.*.email')),
                ],
                'members.*.name' => 'nullable',
                'members.*.phone' => 'nullable|string',
                'members.*.role' => ['nullable', Rule::in(config('settings.roles.1'), config('settings.roles.2'), config('settings.roles.3'), config('settings.roles.4'))],
            ];
        }

        return $roles;
    }
}
