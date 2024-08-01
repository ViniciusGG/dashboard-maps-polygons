<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LicenseRequest;
use App\Http\Requests\StoreLincenseRequest;
use App\Http\Requests\UpdateLincenseRequest;
use App\Models\Lincense;
use App\Repositories\LicenseRepository;
use Illuminate\Http\Request;

/**
 * @group License
 * APIs for managing licenses
 * @authenticated
 * @package App\Http\Controllers\Api

 */
class LicenseController extends Controller
{
    /**
     * Get all licenses
     */
    public function index(Request $request,  LicenseRepository $repository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $data = $repository->getLicenses($filters, $this->workspaceId);

        return $this->apiResponse->successResponse(__('license.list'), $data);
    }

    /**
     * Create a new license
     */

    public function store(LicenseRequest $request, LicenseRepository $repository)
    {
        $requestValidated = $request->validated();
        $license = $repository->create($requestValidated);

        $message = __('license.created');
        return $this->apiResponse->successResponse($message, $license);
    }

    /**
     * Get a license by uuid
     */
    public function show(string $licenseUuid, LicenseRepository $repository)
    {
        $license = $repository->getLicense($licenseUuid);

        return $this->apiResponse->successResponse(__('license.show'), $license);
    }

    /**
     * Update a license
     */
    public function update(LicenseRequest $request, string $licenseUuid, LicenseRepository $repository)
    {
        $requestValidated = $request->validated();
        $license = $repository->updateLicense($licenseUuid, $requestValidated);

        $message = __('license.updated');
        return $this->apiResponse->successResponse($message, $license);
    }

    /**
     * Delete a license
     */
    public function destroy(string $licenseUuid, LicenseRepository $repository)
    {
        $license = $repository->deleteLicense($licenseUuid);

        $message = __('license.deleted');
        return $this->apiResponse->successResponse($message, $license);
    }

    /**
     * Get all permissions
     */
    public function permissions(LicenseRepository $repository)
    {
        $permissions = $repository->getPermissions();

        return $this->apiResponse->successResponse(__('license.permissions'), $permissions);
    }

   
}
