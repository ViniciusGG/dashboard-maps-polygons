<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Models\Workspace;

class BaseRepository
{
    protected $model;
    protected $take = 10;
    protected User $user;

    public function __construct($model)
    {
        $this->model = new $model;
        $this->user = auth()->user() ?? new User();
    }

    public function exists($id)
    {
        return $this->model->findOrFail($id);
    }

    public function existsByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getAllWith($relationships = [])
    {
        if ($relationships) {
            return $this->model->with($relationships)->get();
        }
        return $this->model->get();
    }

    public function getByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function updateByUuid($id, $data)
    {
        $record = $this->getByUuid($id);
        $record->update($data);
        return $record;
    }

    public function deleteByUuid($uuid)
    {
        $record = $this->getByUuid($uuid);
        $record->delete();
        return $record;
    }

    public function update($id, $data)
    {
        $record = $this->getById($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        $record = $this->getById($id);
        $record->delete();
        return $record;
    }

    public function getById($id, $relationships = [])
    {
        if ($relationships) {
            return $this->model->with($relationships)->findOrFail($id);
        }
        return $this->model->findOrFail($id);
    }

    public function getCountryByCode($code, $relationships = [])
    {
        if ($relationships) {
            return $this->model->where('code', $code)->with($relationships)->get();
        }
        return $this->model->where('code', $code)->get();
    }


    public function getWithPaginate($limit = 0, $offset = 0)
    {
        return $this->model->offset((int)$offset)->limit((int)$limit)->get();
    }

    public function get($take = 0, $columns = ["*"], $relationships = [])
    {
        if ($take === 0) {
            $take = $this->take;
        }

        if ($relationships) {
            return $this->model->with($relationships)->paginate($take, $columns);
        }
        return $this->model->paginate($take, $columns);
    }

    public function filter($filters, $queryParams = [], $relationships = [], $appends = [])
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $active = $filters['active'] ?? '';

        if($active == 1 || $active == 0){
            $query->orWhere('active', $active);
        }

        if ($queryParams) {
            foreach ($queryParams as $key => $value) {
                $query->where($key, $value);
            }
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($query) use ($search) {
                $searchFields = empty($this->model->searchFields) ? ['name'] : $this->model->searchFields;
                foreach ($searchFields as $searchField) {
                    $query->orWhere($searchField, 'like', '%' . $search . '%');
                }
            });
        }

        if ($relationships) {
            $query->with($relationships);
        }

        if (isset($filters['sortBy'])) {
            $sortDirection = $filters['sortDirection'] ?? 'ASC';
            $query->orderBy($filters['sortBy'], $sortDirection);
        }

        if ($appends) {
            return $query->paginate($take, $columns)->setAppends($appends);
        }

        return $query->paginate($take, $columns);
    }

    protected function getRoleName($workspaceId, $userId)
    {
        $workspace = Workspace::find($workspaceId);
        if ($workspace) {
            $roleId = $workspace->users()->where('user_id', $userId)->value('role_id');
            if ($roleId) {
                return Role::where('id', $roleId)->value('name');
            }
        }
        return null;
    }
}
