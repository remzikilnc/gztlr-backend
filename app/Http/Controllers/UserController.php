<?php

namespace App\Http\Controllers;

use App\Events\UsersDeleted;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ModifyUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private User $user;
    private Request $request;
    private UserService $userService;

    public function __construct(Request $request, User $user, UserService $userService)
    {
        $this->user = $user;
        $this->request = $request;
        $this->userService = $userService;
    }

    public function index()
    {
        $this->request->user()->hasPermissionTo('users.view');

        $users = $this->userService->index();

        return response()->ok(['users' => $users]);
    }

    public function store(ModifyUserRequest $request)
    {
        $this->authorize('create', $request->user());

        $user = $this->userService->create($request->all());

        return response()->ok(['user' => $user]);
    }

    public function show(User $user)
    {
        $this->authorize('show', $user);

        $this->userService->show($user, $this->request->get('with', ''));

        return response()->ok(['user' => $user]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ModifyUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user = $this->userService->update($user, $request->all());

        return response()->ok(['user' => $user]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        event(new UsersDeleted($user));
        return response()->noContent();
    }
}
