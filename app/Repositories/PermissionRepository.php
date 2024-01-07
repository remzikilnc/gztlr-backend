<?php

namespace App\Repositories;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class
PermissionRepository
{
    public function getAllPermissionsPaginated()
    {
        $roles = QueryBuilder::for(Permission::class)
            ->allowedFilters(['id', 'name', 'description'])
            ->defaultSort('-id')
            ->allowedSorts(['id','name'])
            ->jsonPaginate();


        //Transform anon collect to normal collect
        $transformedData = PermissionResource::collection($roles->getCollection())->resolve(); // toArray
        $transformedCollection = collect($transformedData); //toCollect

        return $roles->setCollection($transformedCollection);
    }

}
