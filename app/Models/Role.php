<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function getGuestRole()
    {
        return $this->where('guests', 1)->where('guard_name', 'api')->first();;
    }

    public function getDefaultRole()
    {
        return $this->where('default', 1)->where('guard_name', 'api')->first();
    }
}
