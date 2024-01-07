<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('permission.viewAny');

        $permissions = $this->permissionService->index();

        return response()->ok($permissions);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Permission $permission)
    {
        $this->authorize('permission.view', $permission);

        $response = $this->permissionService->show($permission);

        return response()->ok(['role' => $response]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('permission.update', $permission);

        $response = $this->permissionService->update($permission, $request->all());

        return response()->ok(['role' => $response]);
    }
}
