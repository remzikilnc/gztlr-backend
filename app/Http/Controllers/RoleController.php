<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private Role $role;
    private Request $request;
    private RoleService $roleService;

    public function __construct(Request $request, Role $role, RoleService $roleService)
    {
        $this->role = $role;
        $this->request = $request;
        $this->roleService = $roleService;
    }

    public function index()
    {
        $this->request->user()->hasPermissionTo('roles.viewAny');

        $roles = $this->roleService->index();

        return response()->ok($roles);
    }


    /**
     * @throws AuthorizationException
     */
    public function store()
    {
        $this->authorize('create', Role::class);

        $role = $this->roleService->create($this->request->all());

        return response()->ok(['role' => $role]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('roles.view', $role);

        $response = $this->roleService->show($role);

        return response()->ok(['role' => $response]);
    }


    /**
     * @throws AuthorizationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('roles.update', $role);

        $response = $this->roleService->update($role, $request->all());

        return response()->ok(['role' => $response]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Role $role)
    {
        $this->authorize('roles.delete', $role);

        $this->roleService->destroy($role);

        return response()->noContent();
    }
}
