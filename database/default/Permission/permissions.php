<?php

return [
    'roles' => [
        [
            'name' => 'admin',
            'guard_name' => 'api',
            'permissions' => [
                'roles.viewAny',
                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',
                'roles.assign.all',
                'roles.assign.admin',
                'roles.assign.editor',
                'roles.assign.user',
                'roles.assign.guest',

                'users.viewAny',
                'users.view',
                'users.create',
                'users.update.all',
                'users.update.admin',
                'users.update.editor',
                'users.update.user',

                'users.delete.all',
                'users.delete.admin',
                'users.delete.editor',
                'users.delete.user',
                'users.delete.guest',

                'users.statistics',

                'games.viewAny',
                'games.view',
                'games.create',
                'games.update',
                'games.delete',
                'games.statistics',

                'categories.viewAny',
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete',
            ],
        ],
        [
            'name' => 'editor',
            'guard_name' => 'api',
            'permissions' => [

                'users.viewAny',

                'users.view',

                'users.update.user',

                'users.delete.user',

                'roles.assign.user',

                'games.viewAny',
                'games.view',
                'games.create',
                'games.update',
                'games.statistics',

                'categories.viewAny',
                'categories.view',
                'categories.create',
                'categories.update',
                'categories.delete',
            ],
        ],
        [
            'name' => 'user',
            'guard_name' => 'api',
            'default' => true,
            'permissions' => [

            ],
        ],
        [
            'name' => 'guest',
            'guard_name' => 'api',
            'guest' => true,
            'permissions' => [
            ],
        ]
    ],
    'permissions' => [
        'roles' => [
            [
                'name' => 'assign.all',
                'description' => 'Allow assigning any role to users, regardless of the role hierarchy.'
            ],
            [
                'name' => 'assign.admin',
                'description' => 'Allow assigning the "admin" role to users.'
            ],
            [
                'name' => 'assign.editor',
                'description' => 'Allow assigning the "editor" role to users.'
            ],
            [
                'name' => 'assign.user',
                'description' => 'Allow assigning the "user" role to users.'
            ],
            [
                'name' => 'assign.guest',
                'description' => 'Allow assigning the "guest" role to users.'
            ],
            [
                'name' => 'viewAny',
                'description' => 'Allow viewing the list of all roles available on the site.'
            ],
            [
                'name' => 'view',
                'description' => 'Allow viewing the details of individual roles on the site.'
            ],
            [
                'name' => 'create',
                'description' => 'Allow creating new roles on the site.'
            ],
            [
                'name' => 'update',
                'description' => 'Allow editing the details and permissions of any role on the site.'
            ],
            [
                'name' => 'delete',
                'description' => 'Allow deleting any role from the site.'
            ],
        ],
        'users' => [
            [
                'name' => 'viewAny',
                'description' => 'Allow viewing all users.'
            ],
            [
                'name' => 'view',
                'description' => 'Allow viewing requested user model. User can view their own model without this permission.'
            ],
            [
                'name' => 'create',
                'description' => 'Allow creating users. Users can register for new accounts without this permission. Registration can be disabled from settings.',
            ],
            [
                'name' => 'update.all',
                'description' => 'Allow editing details of any user on the site, regardless of their role. Users can still edit their own details without this permission.',
            ],
            [
                'name' => 'update.admin',
                'description' => 'Allow editing details of users with the "admin" role. Users can still edit their own details without this permission.',
            ],
            [
                'name' => 'update.editor',
                'description' => 'Allow editing details of users with the "editor" role. Users can still edit their own details without this permission.',
            ],
            [
                'name' => 'update.user',
                'description' => 'Allow editing details of users with the "user" role. Users can still edit their own details without this permission.',
            ],
            [
                'name' => 'update.guest',
                'description' => 'Allow editing details of users with the "user" role. Users can still edit their own details without this permission.',
            ],
            [
                'name' => 'update.emailverified',
                'description' => 'Allow updating user email verified at time.',
            ],
            [
                'name' => 'delete.all',
                'description' => 'Allow deleting any user on the site, regardless of their role. Users can still request their own account deletion without this permission.',
            ],
            [
                'name' => 'delete.admin',
                'description' => 'Allow deleting users with the "admin" role. Users can still request their own account deletion without this permission.',
            ],
            [
                'name' => 'delete.editor',
                'description' => 'Allow deleting users with the "editor" role. Users can still request their own account deletion without this permission.',
            ],
            [
                'name' => 'delete.user',
                'description' => 'Allow deleting users with the "user" role. Users can still request their own account deletion without this permission.',
            ],
            [
                'name' => 'delete.guest',
                'description' => 'Allow deleting users with the "user" role. Users can still request their own account deletion without this permission.',
            ],
            [
                'name' => 'suspend.all',
                'description' => 'Allow suspending or unsuspending any user on the site.',
            ],
            [
                'name' => 'suspend.admin',
                'description' => 'Allow suspending or unsuspending users with the "admin" role.',
            ],
            [
                'name' => 'suspend.editor',
                'description' => 'Allow suspending or unsuspending users with the "editor" role.',
            ],
            [
                'name' => 'suspend.user',
                'description' => 'Allow suspending or unsuspending users with the "user" role.',
            ],
            [
                'name' => 'statistics',
                'description' => 'Allow viewing users statistics.'
            ]
        ],
        'games' => [
            [
                'name' => 'viewAny',
                'description' => 'Allow viewing all games.'
            ],
            [
                'name' => 'view',
                'description' => 'Allow viewing requested game model.'
            ],
            [
                'name' => 'create',
                'description' => 'Allow creating games.',
            ],
            [
                'name' => 'update',
                'description' => 'Allow editing details of any game on the site.',
            ],
            [
                'name' => 'delete',
                'description' => 'Allow deleting games.',
            ],
            [
                'name' => 'statistics',
                'description' => 'Allow viewing games statistics.'
            ]
        ],
        'categories' => [
            [
                'name' => 'viewAny',
                'description' => 'Allow viewing all categories.'
            ],
            [
                'name' => 'view',
                'description' => 'Allow viewing requested category model.'
            ],
            [
                'name' => 'create',
                'description' => 'Allow creating categories.',
            ],
            [
                'name' => 'update',
                'description' => 'Allow editing details of any category on the site.',
            ],
            [
                'name' => 'delete',
                'description' => 'Allow deleting categories.',
            ]
        ],
    ],
];
