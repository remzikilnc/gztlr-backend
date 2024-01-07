<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait RelationLoader
{
    protected function filterLoadableRelations(array|string $requestedRelations, string $modelClass): array
    {
        $requested = $this->getRequestedRelationsAsCollection($requestedRelations);

        return $requested->filter(function ($relation) use ($modelClass) {
            $loadableRelations = $modelClass::getloadableRelations();
            return isset($loadableRelations[$relation]) && ($this->checkPermission($loadableRelations[$relation]) || !$this->isPermissionNeeded($loadableRelations[$relation]));
        })->values()->toArray();
    }

    private function getRequestedRelationsAsCollection(array|string $requestedRelations): Collection
    {
        if (is_string($requestedRelations)) {
            $requestedRelations = explode(',', $requestedRelations);
        }
        return collect($requestedRelations);
    }

    private function isPermissionNeeded(string $permission): bool
    {
        return trim($permission) !== '';
    }

    private function checkPermission(string $permission): bool
    {
        return Auth::user()->can($permission);
    }
}
