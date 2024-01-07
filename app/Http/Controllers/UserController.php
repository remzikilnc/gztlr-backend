<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ModifyUserRequest;
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
        $this->authorize('view', $this->request->user());

        $users = $this->userService->index();

        return response()->ok($users);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ModifyUserRequest $request)
    {
        $this->authorize('create', $request->user());

        $user = $this->userService->create($request->all());

        return response()->ok(['user' => $user]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('show', $user);

        $response = $this->userService->show($user, $this->request->get('with', ''));

        return response()->ok(['user' => $response]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ModifyUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $filteredData = array_filter($request->all(), fn($value) => !is_null($value) && $value !== '');

        $response = $this->userService->update($user, $filteredData);

        return response()->ok($response);
    }

    public function statistics()
    {
        $this->authorize('statistics', $this->request->user());

        $response = $this->userService->statistics();

        return response()->ok($response);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === $this->request->user()->id) {
            return response()->badRequest('You cannot delete yourself.');
        }

        $this->userService->destroy($user);

        return response()->noContent();
    }
}
