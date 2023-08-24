<?php

return [
    'roles' => [
        [
            'name' => 'admin',
            'guard_name' => 'api',
            'permissions' => [
                'roles.assign.all',
                'users.roles.view',
                'users.view',
                'users.show',
                'users.create',
                'users.update',
                'users.delete'
            ],
        ],
        [
            'name' => 'editor',
            'guard_name' => 'api',
            'permissions' => [
                'roles.assign.user',
                'users.view',
                'users.show',
                'users.create',
                'users.update',
                'users.delete'
            ],
        ],
        [
            'name' => 'user',
            'guard_name' => 'api',
            'default' => true,
            'permissions' => [
                'users.view'
            ],
        ],
        [
            'name' => 'guest',
            'guard_name' => 'api',
            'guests' => true,
            'permissions' => [
            ],
        ]
    ],
    'permissions' => [
        'roles' => [
            [
                'name' => 'assign.all',
                'description' => 'Allow assign all roles.'
            ],
            [
                'name' => 'assign.admin',
                'description' => 'Allow assigning the admin role to users.'
            ],
            [
                'name' => 'roles.assign.user',
                'description' => 'Allow assigning the user role to users.'
            ],
        ],
        'users' => [
            [
                'name' => 'view',
                'description' => 'Allow viewing all users.'
            ],
            [
                'name' => 'show',
                'description' => 'Allow viewing requested user model. User can view their own model without this permission.'
            ],
            [
                'name' => 'create',
                'description' => 'Allow creating users. Users can register for new accounts without this permission. Registration can be disabled from settings.',
            ],
            [
                'name' => 'update',
                'description' => 'Allow editing details of any user on the site. User can edit their own details without this permission.',
            ],
            [
                'name' => 'delete',
                'description' => 'Allow deleting any user on the site. User can request deletion of their own account without this permission.',
            ], [
                'name' => 'users.roles.view',
                'description' => 'Allow viewing any user roles on the site.',
            ],
        ],
    ],
];
