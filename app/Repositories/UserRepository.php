<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    public function getAllUsersPaginated($params): AnonymousResourceCollection
    {
        $allowedIncludes = $params['includes'] ?? [];

        $allowedFilters = ['first_name', 'last_name', 'email'];


        if (in_array('roles', $allowedIncludes)) {
            $allowedFilters[] = AllowedFilter::exact('roles.name');
        }

        return UserResource::collection(QueryBuilder::for(User::class)
            ->with($allowedIncludes)
            ->allowedIncludes($allowedIncludes)
            ->allowedFilters($allowedFilters)
            ->defaultSort('-id')
            ->allowedSorts(['first_name', 'last_name', 'email', 'id'])
            ->jsonPaginate());
    }
}
