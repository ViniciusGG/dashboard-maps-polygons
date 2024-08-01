<?php

namespace App\Repositories;

use App\Models\Filter;
use App\Models\Indicator;
use App\Models\License;
use App\Models\LicensePermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Services;
use Illuminate\Support\Str;

class LicenseRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(License::class);
    }

    public function create($dataValidated)
    {
        $dataLicense['name'] = $dataValidated['name'];
        $dataLicense['members'] = $dataValidated['members'];
        $dataLicense['color'] = $dataValidated['color'];
        $license = $this->model->create($dataLicense);

        if (isset($dataValidated['services'])) {
            $this->attachServices($dataValidated['services'], $license);
        }

        if (isset($dataValidated['filters'])) {
            $this->attachFilters($dataValidated['filters'], $license);
        }

        if (isset($dataValidated['indicators'])) {
            $this->attachIndicators($dataValidated['indicators'], $license);
        }

        isset($dataValidated['admin_role']) ? $this->attachPermissions($dataValidated['admin_role'], 2, $license->id) : null;
        isset($dataValidated['technicians_role']) ?  $this->attachPermissions($dataValidated['technicians_role'], 3, $license->id) : null;
        isset($dataValidated['external_service_provider_role']) ?  $this->attachPermissions($dataValidated['external_service_provider_role'], 4, $license->id) : null;

        return $license->load('services', 'filters', 'indicators', 'licensePermissionsAdmin', 'licensePermissionsTechnician', 'licensePermissionsExternalServiceProvider')->toArray();
    }

    public function getLicenses($filters)
    {
        $query = $this->model->newQuery();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['sortBy'])) {
            $query->orderBy($filters['sortBy'], $filters['sortDirection']);
        }

        if (isset($filters['take'])) {
            return $query->paginate($filters['take']);
        }

        $license = $query->get()->toArray();
        return $license;
    }

    public function getLicense($licenseUuid)
    {
        $query = $this->model->newQuery();
        $query->where('uuid', $licenseUuid);
        $query->with('services');
        $query->with('filters');
        $query->with('indicators');
        $query->with('licensePermissionsAdmin');
        $query->with('licensePermissionsTechnician');
        $query->with('licensePermissionsExternalServiceProvider');

        $license = $query->firstOrFail();
        $license->services->map(function ($service) {
            $service->name = __("license.{$service->name}");
            return $service;
        });
        $license->filters->map(function ($filter) {
            $filter->name = __("license.{$filter->name}");
            return $filter;
        });
        $license->indicators->map(function ($indicator) {
            // $indicator->name = __("license.{$indicator->name}");
            return $indicator;
        });
        $license->setRelation('license_permissions_admin', $license->licensePermissionsAdmin->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));
        $license->setRelation('license_permissions_technician', $license->licensePermissionsTechnician->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));
        $license->setRelation('license_permissions_external_service_provider', $license->licensePermissionsExternalServiceProvider->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));


        return ['license' => $license, 'permissions' => $this->getPermissions()];
    }

    public function updateLicense($licenseUuid, $dataValidated)
    {
        $query = $this->model->newQuery();
        $query->where('uuid', $licenseUuid);
        $query->with('services');
        $query->with('filters');
        $query->with('indicators');
        $query->with('licensePermissionsAdmin');
        $query->with('licensePermissionsTechnician');
        $query->with('licensePermissionsExternalServiceProvider');

        $license = $query->firstOrFail();

        $dataLicense['name'] = $dataValidated['name'];
        $dataLicense['members'] = $dataValidated['members'];
        $dataLicense['color'] = $dataValidated['color'];
        $license->update($dataLicense);

        $license->services()->detach();
        $license->filters()->detach();
        $license->indicators()->detach();

        if (isset($dataValidated['services'])) {
            $this->attachServices($dataValidated['services'], $license);
        }

        if (isset($dataValidated['filters'])) {
            $this->attachFilters($dataValidated['filters'], $license);
        }

        if (isset($dataValidated['indicators'])) {
            $this->attachIndicators($dataValidated['indicators'], $license);
        }

        LicensePermissions::where('license_id', $license->id)->delete();

        isset($dataValidated['admin_role']) ? $this->attachPermissions($dataValidated['admin_role'], 2, $license->id) : null;
        isset($dataValidated['technicians_role']) ?  $this->attachPermissions($dataValidated['technicians_role'], 3, $license->id) : null;
        isset($dataValidated['external_service_provider_role']) ?  $this->attachPermissions($dataValidated['external_service_provider_role'], 4, $license->id) : null;

        $license->services->map(function ($service) {
            $service->name = __("license.{$service->name}");
            return $service;
        });
        $license->filters->map(function ($filter) {
            $filter->name = __("license.{$filter->name}");
            return $filter;
        });
        $license->indicators->map(function ($indicator) {
            // $indicator->name = __("license.{$indicator->name}");
            return $indicator;
        });

        $license->setRelation('license_permissions_admin', $license->licensePermissionsAdmin->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));
        $license->setRelation('license_permissions_technician', $license->licensePermissionsTechnician->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));
        $license->setRelation('license_permissions_external_service_provider', $license->licensePermissionsExternalServiceProvider->map(function ($permission) {
            return [
                'uuid' => $permission->permissions->uuid,
                'name' => __("license.{$permission->permissions->name}"),
            ];
        }));



        return $license->toArray();
    }

    public function deleteLicense($licenseUuid)
    {
        $license = $this->model->where('uuid', $licenseUuid)->firstOrFail();
        $license->delete();
        return $license->load('services', 'filters', 'indicators', 'licensePermissionsAdmin', 'licensePermissionsTechnician', 'licensePermissionsExternalServiceProvider')->toArray();
    }

    public function attachPermissions($permissionUuids, $roleId, $licenseId)
    {
        foreach ($permissionUuids as $permissionUuid) {
            $permission = Permission::where('uuid', $permissionUuid)->firstOrFail();
            LicensePermissions::create([
                'license_id' => $licenseId,
                'permission_id' => $permission->id,
                'role_id' => $roleId,
            ]);
        }
    }

    private function attachServices($serviceUuids, $license)
    {
        foreach ($serviceUuids as $serviceUuid) {
            $service = Services::where('uuid', $serviceUuid)->firstOrFail();
            $license->services()->attach($service->id, ['created_at' => now(), 'updated_at' => now(), 'uuid' => Str::uuid()]);
        }
    }

    private function attachFilters($filterUuids, $license)
    {
        foreach ($filterUuids as $filterUuid) {
            $filter = Filter::where('uuid', $filterUuid)->firstOrFail();
            $license->filters()->attach($filter->id, ['created_at' => now(), 'updated_at' => now(), 'uuid' => Str::uuid()]);
        }
    }

    private function attachIndicators($indicatorUuids, $license)
    {
        foreach ($indicatorUuids as $indicatorUuid) {
            $indicator = Indicator::where('uuid', $indicatorUuid)->firstOrFail();
            $license->indicators()->attach($indicator->id, ['created_at' => now(), 'updated_at' => now(), 'uuid' => Str::uuid()]);
        }
    }

    public function getPermissions()
    {
        $services = Services::all();
        $filters = Filter::all();
        $indicators = Indicator::all()->map(function ($indicator) {
            return [
                'uuid' => $indicator->uuid,
                'name' => $indicator->name,
            ];
        });


        $adminPermissions = Role::findByName('admin')->permissions()->get(['name', 'id', 'uuid'])->toArray() ?? [];
        $techniciansPermissions = Role::findByName('technicians')->permissions()->get(['name', 'id', 'uuid'])->toArray() ?? [];
        $externalServiceProviderPermissions = Role::findByName('external_service_provider')->permissions()->get(['name', 'id', 'uuid'])->toArray() ?? [];

        $translateNames = function ($item) {
            return [
                'uuid' => $item['uuid'],
                'name' => __("license.{$item['name']}"),
            ];
        };

        return [
            'services' => $services->map($translateNames),
            'filters' => $filters->map($translateNames),
            'indicators' => $indicators,
            'admin_role' => collect($adminPermissions)->map($translateNames),
            'technicians_role' => collect($techniciansPermissions)->map($translateNames),
            'external_service_provider_role' => collect($externalServiceProviderPermissions)->map($translateNames),
        ];
    }
}
