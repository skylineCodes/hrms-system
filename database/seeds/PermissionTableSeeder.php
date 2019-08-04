<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'employee-create',
            'employee-read',
            'employee-update',
            'employee-delete',
            'role-create',
            'role-read',
            'role-update',
            'role-delete',
            'task-create',
            'task-read',
            'task-update',
            'task-delete',
            'leave-create',
            'leave-read',
            'leave-update',
            'leave-delete',
            'hire-create',
            'hire-read',
            'hire-update',
            'hire-delete'
        ];

        foreach($permissions as $permission)
        {
            Permission::create(['name' => $permission, 'guard_name' => 'adminapi']);
        }
    }
}
