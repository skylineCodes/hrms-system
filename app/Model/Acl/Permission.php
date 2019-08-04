<?php

namespace App\Model\Acl;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Model;

class Permission extends SpatiePermission
{
    protected $guard_name = 'adminapi';

    public static function defaultPermissions()
    {
        return [
            'create_employees',
            'read_employees',
            'update_employees',
            'delete_employees',

            'create_roles',
            'read_roles',
            'update_roles',
            'delete_roles',

            'create_leaves',
            'read_leaves',
            'update_leaves',
            'delete_leaves',
        ];
    }
}
