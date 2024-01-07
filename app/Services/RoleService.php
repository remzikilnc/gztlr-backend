<?php

namespace App\Services;

use App\Events\Role\RoleCreated;
use App\Events\Role\RoleDeleted;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleService extends BaseService
{
    protected Role $role;
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository, Role $role)
    {
        $this->role = $role;
        $this->roleRepository = $roleRepository ;
    }

    public function index()
    {
        return app(RoleRepository::class)->getAllRolesPaginated();
    }

    public function create(array $params): RoleResource
    {
        $role = $this->role->create($params);
        event(new RoleCreated($role));
        return new RoleResource($role->fresh());
    }

    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
    }

    public function update(Role $role, array $params): RoleResource
    {
        $role->fill($params);
        $role->save();
        return new RoleResource($role->fresh());
    }

    public function destroy(Role $role): void
    {
        $role->users()->detach();
        $role->delete();
        event(new RoleDeleted($role));
    }
}
