<?php

namespace Database\Default;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GetStaticPermissions
{

    private array $defaultPermissionList;

    public function __construct()
    {
        $this->defaultPermissionList = include __DIR__ . '/permissions.php';
    }

    public function getRoles(): array
    {
        return  $this->defaultPermissionList['roles'];

    }

    public function getPermissions(): array {
       return $this->defaultPermissionList['permissions'];
    }
}
