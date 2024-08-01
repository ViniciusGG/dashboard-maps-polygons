<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckEmailRequest;
use App\Http\Requests\Api\PasswordExpiredRequest;
use App\Http\Requests\Api\UserStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest as ApiUserUpdateRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;

/**
 * @group User
 * APIs for managing users
 * @authenticated
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    use HasRoles;

    /**
     * List all users
     * @param UserRepository $repository
     * @return array
     */
    public function index(Request $request, UserRepository $repository)
    {
        $this->enableFilters();

        $filters = $this->getFilters($request);

        if ($filters) {
            $data = $repository->userFilter($filters);
        } else {
            $data = $repository->getUser(0, ['*']);
        }

        return $this->apiResponse->successResponse(__('user')['index'], $data->toArray());
    }

    /**
     * Delete a user
     * @param UserRepository $repository
     * @return array
     */
    public function destroy(string $userUuid, UserRepository $repository)
    {
        $user = $repository->destroyUser($userUuid);

        return $this->apiResponse->successResponse(__('user')['destroy'], $user->toArray());
    }

    /**
     * Create a new user
     * @param UserRepository $repository
     * @return array
     */
    public function store(UserStoreRequest $request, UserRepository $repository)
    {
        $requestValidated = $request->validated();

        $user = $repository->createUser($requestValidated);

        return $this->apiResponse->successResponse(__('user')['store'], $user->toArray());
    }

     /**
     * Update a user
     * @param UserRepository $repository
     * @bodyParam password string required The password of the user. Example: John Doe
     * @bodyParam password string required The password of the user. Example: password
     * @bodyParam password_confirmation string required The password of the user. Example: password
     *
     * @return array
     */
    public function update(ApiUserUpdateRequest $request, string $userUuid, UserRepository $repository)
    {
        $requestValidated = $request->validated();

        $user = $repository->updateUser($userUuid, $requestValidated);

        return $this->apiResponse->successResponse(__('user')['updated'], $user->toArray());
    }

     /**
     * Show a user
     * @param UserRepository $repository
     * @return array
     */
    public function show(string $userUuid, UserRepository $repository)
    {
        $user = $repository->showUser($userUuid);

        return $this->apiResponse->successResponse(__('user')['show'], $user->toArray());
    }

     /**
     * Password Expired
     * @param UserRepository $repository
     * @bodyParam password string required The password of the user. Example: password
     * @bodyParam password_confirmation string required The password of the user. Example: password
     * @return array
     */
    public function passwordExpired(PasswordExpiredRequest $request, UserRepository $userRepository)
    {
        $user = auth()->user();
        $data = $request->validated();

        $status = $userRepository->changePasswordExpired($user->id, $data['password']);

        if ($status) {
            $response = $this->apiResponse->successResponse('Password changed with success', []);
        } else {
            $response = $this->apiResponse->errorResponse('For security reasons, you cannot use the same password twice.', 400, 400);
        }

        return $response;
    }

    /**
     * Check if email exists
     * @param UserRepository $repository
     * @return array
     */
    public function checkEmail(CheckEmailRequest $request, UserRepository $userRepository)
    {
        $email = $request->email;
        $user = $userRepository->checkEmail($email);

        if ($user) {
            $response = $this->apiResponse->errorResponse(__('workspace.already_on_workspace'), 400, 400);
        } else {
            $response = $this->apiResponse->successResponse('Email available', []);
        }

        return $response;
    }

}
