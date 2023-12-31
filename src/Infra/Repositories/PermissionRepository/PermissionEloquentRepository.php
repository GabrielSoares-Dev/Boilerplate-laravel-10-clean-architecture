<?php

namespace Src\Infra\Repositories\PermissionRepository;

use Spatie\Permission\Models\Permission;
use Src\Domain\Repositories\PermissionRepositoryInterface;

class PermissionEloquentRepository implements PermissionRepositoryInterface
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function create(array $input)
    {
        return $this->model
            ->create($input);
    }

    public function find(string $id)
    {
        return $this->model
            ->where('id', $id)
            ->first();
    }

    public function findByName(array $input)
    {
        return $this->model
            ->where('guard_name', $input['guard_name'])
            ->where('name', $input['name'])
            ->first();
    }

    public function findAll()
    {
        return $this->model
            ->where('guard_name', 'api')
            ->get();
    }

    public function update(array $input, string $id)
    {
        return $this->model
            ->where('id', $id)
            ->update($input);
    }

    public function delete(string $id)
    {
        return $this->model
            ->where('id', $id)
            ->delete();
    }
}
