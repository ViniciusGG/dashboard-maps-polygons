<?php

namespace App\Repositories;

use App\Mail\UserCreated;
use App\Models\User;
use App\Models\WorkspaceMember;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class UserRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function userFilter($filters)
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;

        if (isset($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($query) use ($search) {
                $searchFields = ['name', 'email'];
                foreach ($searchFields as $searchField) {
                    $query->orWhere($searchField, 'like', '%' . $search . '%');
                }
            });
        }

        if (isset($filters['sortBy'])) {
            $sortDirection = $filters['sortDirection'] ?? 'ASC';
            $query->orderBy($filters['sortBy'], $sortDirection);
        }

        return $query->paginate($take, $columns);
    }

    public function getUser($take = 0, $columns = ["*"])
    {
        if ($take === 0) {
            $take = $this->take;
        }

        $query = $this->model->newQuery();

        return $query->paginate($take, $columns);
    }

    public function createUser($dataValidated)
    {
        $email = config('app.key') . $dataValidated['email'];
        $emailDecrypt = base64_encode($email);
        $user = $this->model->where('email', $emailDecrypt)->first();
        if ($user) {
            return $user;
        }

        $randomPassword = Str::random(16);

        $user = $this->model->create([
            'email' => strtolower($dataValidated['email']) ?? null,
            'name' => $dataValidated['name'] ?? null,
            'phone' => $dataValidated['phone'] ?? null,
            'password' => Hash::make($randomPassword),
            'password_expires_at' => now(),
        ]);
        try {
            Mail::to($user->email)->send(new UserCreated($user, $randomPassword));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $user;
    }

    public function updateUser($uuid, $dataValidated)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        if(isset($dataValidated['password']) && !Hash::check($dataValidated['password'], $user->password))
        {
            $dataValidated['password'] = Hash::make($dataValidated['password']);
            $dataValidated['password_expires_at'] = now()->addDays(config('settings.expired_password_days'));
        }
        $user->update($dataValidated);

        return $user;
    }

    public function destroyUser($uuid)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        $user->delete();
        return $user;
    }

    public function showUser($uuid)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        return $user;
    }

    public function changePasswordExpired($id, mixed $password)
    {
        $user = $this->model->findOrFail($id);

        if (Hash::check($password, $user->password)) {
            return false;
        }

        return $user->update([
            'password' => Hash::make($password),
            'password_expires_at' => now()->addDays(config('settings.expired_password_days'))
        ]);
    }

    public function checkEmail($email)
    {
        $email = config('app.key') . $email;
        $emailDecrypt = base64_encode($email);
        $user = $this->model->where('email', $emailDecrypt)->first();
        if(!$user) {
            return false;
        }
        $workspaceMember = WorkspaceMember::where('user_id', $user->id)->first();
        if ($workspaceMember) {
            return true;
        }
        return false;
    }

}
