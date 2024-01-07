<?php

namespace App\Services;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Repositories\PermissionRepository;

class PermissionService
{

    protected Permission $permission;
    protected PermissionRepository $roleRepository;

    public function __construct(PermissionRepository $roleRepository, Permission $permission)
    {
        $this->permission = $permission;
        $this->roleRepository = $roleRepository ;
    }

    public function index()
    {
        return app(PermissionRepository::class)->getAllPermissionsPaginated();
    }

    public function show(Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }

    public function update(Permission $permission, array $params): PermissionResource
    {
        $permission->fill($params);

        $permission->save();

        return new PermissionResource($permission->fresh());
    }
}
