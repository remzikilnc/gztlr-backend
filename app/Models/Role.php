<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function getGuestRoleForApi()
    {
        return $this->where('guests', 1)->where('guard_name', 'api')->first();;
    }

    public function getDefaultRoleForApi()
    {
        return $this->where('default', 1)->where('guard_name', 'api')->first();
    }

    public function getGuestAttribute($value): bool
    {
        return $value === 't';
    }

    public function getDefaultAttribute($value): bool
    {
        return $value === 't';
    }
}
