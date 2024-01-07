<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    public function getAllUsersPaginated()
    {
        $users = QueryBuilder::for(User::class)
            ->with('roles')
            ->allowedIncludes('roles')
            ->allowedFilters('first_name', 'last_name', 'email', AllowedFilter::exact('roles.name'),
                AllowedFilter::scope('search', 'search'),
            )
            ->defaultSort('-id')
            ->allowedSorts(['first_name', 'last_name', 'email', 'id'])
            ->jsonPaginate();

        return UserResource::collection($users)->response()->getData(true);
    }

    public function getRegisteredUsersForGivenWeek(Carbon $weekStart, Carbon $weekEnd): array
    {
        $stats = DB::table('users')
            ->select(
                DB::raw("COALESCE(COUNT(*), 0) as total_users"),
                DB::raw("COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0) as active_users"),
                DB::raw("COALESCE(SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END), 0) as passive_users")
            )
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->first();

         return [
             'week_start' => $weekStart->toDateString(),
             'week_end' => $weekEnd->toDateString(),
             'total_users' => (int) $stats->total_users,
             'active_users' => (int) $stats->active_users,
             'passive_users' => (int) $stats->passive_users
         ];
    }
}
