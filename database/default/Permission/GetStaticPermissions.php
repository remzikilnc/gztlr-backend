<?php

namespace Database\Default\Permission;

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
