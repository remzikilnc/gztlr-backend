<?php

namespace App\Services;

use App\Events\User\UserCreated;
use App\Events\User\UserDeleted;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\AuthorizedRelationLoader;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Permission;

class UserService
{
    use AuthorizedRelationLoader;

    protected User $user;
    protected Role $role;
    protected Permission $permission;
    protected UserRepository $userRepository;

    public function __construct(User $user,UserRepository $userRepository, Role $role, Permission $permission)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->userRepository = $userRepository ;
    }

    public function index()
    {
        return app(UserRepository::class)->getAllUsersPaginated();
    }

    /**
     * @throws Exception
     */
    public function create(array $params)
    {
        $user = $this->user->create($params);
        $params = collect($params);

        if ($params->has('roles')) {
            if (!$this->syncRoles($user, $params->get('roles'))) {
                $this->assignDefaultRole($user);
            }
        } else {
            $this->assignDefaultRole($user);
        }

        event(new UserCreated($user));

        return new UserResource($user);
    }

    public function show(User $user, string $requestedRelations): UserResource
    {
        $loadableRelations = $this->filterLoadableRelations($requestedRelations, User::class);

        $user->load($loadableRelations);

        return new UserResource($user);
    }

    public function update(User $user, array $params)
    {
        $filteredParams = collect($params)->except('email_verified_at')->toArray();
        $user->fill($filteredParams);
        $params = collect($params);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            // $user->sendEmailVerificationNotification();
        }
        if ($user->isDirty('password')) {
            $user->password = Hash::make($params->get('password'));
        }

        if ($params->has('email_verified_at')) { //fill email_verified_at if authenticated user can be
            if (Auth::user()->can('updateEmailVerifiedAt', Auth::user())) {
                $user->email_verified_at = $params['email_verified_at'];
            }
        }

        if ($params->has('roles')) {
           $this->syncRoles($user, $params->get('roles'));
        }

        $user->save();

        return new UserResource($user->fresh());
    }

    public function statistics() {
        $result = DB::select(
              "SELECT
                        COUNT(*) as total_users,
                        SUM(CASE WHEN status = true THEN 1 ELSE 0 END) as active_users,
                        SUM(CASE WHEN status = false THEN 1 ELSE 0 END) as inactive_users,
                        SUM(CASE WHEN created_at >= CURRENT_DATE - INTERVAL 7 DAY THEN 1 ELSE 0 END) as new_users_last_7_days,
                        SUM(CASE WHEN created_at < CURRENT_DATE - INTERVAL 7 DAY AND created_at >= CURRENT_DATE - INTERVAL 14 DAY THEN 1 ELSE 0 END) as new_users_previous_7_days
                    FROM users;"
        );

        $newUsersLast7Days = $result[0]->new_users_last_7_days;
        $newUsersPrevious7Days = $result[0]->new_users_previous_7_days;

        if ($newUsersPrevious7Days == 0) {
            $userGrowthPercentageComparedToLastWeek = $newUsersLast7Days > 0 ? 100 : 0;
        } else {
            $userGrowthPercentageComparedToLastWeek = (($newUsersLast7Days - $newUsersPrevious7Days) / $newUsersPrevious7Days) * 100;
        }

        return [
            'total_users' => $result[0]->total_users,
            'active_users' => $result[0]->active_users,
            'inactive_users' => $result[0]->inactive_users,
            'new_users_last_7_days' => $result[0]->new_users_last_7_days,
            'new_users_previous_7_days' => $result[0]->new_users_previous_7_days,
            'user_growth_percentage_compared_to_last_week' => number_format($userGrowthPercentageComparedToLastWeek, 2),
        ];
    }

    public function destroy(User $user){
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        event(new UserDeleted($user));
    }

    /**
     * @throws AuthorizationException
     */
    protected function syncRoles(User $user, array $roles): bool|User
    {
        $validRoles = $this->filterValidRoles($roles);

        if (empty($validRoles)) {
            return false;
        }

        $allowedRoles = [];

        foreach ($validRoles as $role) {
            if (Auth::user()->can('assignRole', [$user, $role])) {
                $allowedRoles[] = $role;
            } else {
                Log::warning("User with ID: " . Auth::id() . " tried to assign the role: {$role} without permission.");
                throw new AuthorizationException('You do not have permission to assign this role.');
            }
        }

        $user->syncRoles($allowedRoles);

        return true;
    }

    protected function filterValidRoles(array $roles): array
    {
        $validRoles = [];

        foreach ($roles as $roleName) {
            try {
                $role = $this->role->findByName($roleName, 'api');
                $validRoles[] = $role->name;
            } catch (RoleDoesNotExist $e) {
                continue;
            }
        }

        return $validRoles;
    }

    private function assignDefaultRole(User $user): void
    {
        $defaultRole = $this->role->getDefaultRole();

        if ($defaultRole) {
            $user->assignRole($defaultRole);
        }
    }
}
