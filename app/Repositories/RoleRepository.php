<?php

namespace App\Repositories;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRepository
{
    public function getAllRolesPaginated()
    {
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters(['id', 'name'])
            ->defaultSort('-id')
            ->allowedSorts(['id','name'])
            ->jsonPaginate();


        //Transform anon collect to normal collect
        $transformedData = RoleResource::collection($roles->getCollection())->resolve(); // toArray
        $transformedCollection = collect($transformedData); //toCollect

        return $roles->setCollection($transformedCollection);
    }

}
