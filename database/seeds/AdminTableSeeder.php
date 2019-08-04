<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Model\Admin\Admin;
use App\Model\Admin\Profile;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin;

        $role = Role::findByName('superadmin', 'adminapi');

        $adminCredentials = $admin->create([
            'username' => 'Korede',
            'email' => 'korede@almondcareers.com',
            'password' => bcrypt('password'),
            'region' => strtoupper('ng')
        ]);

        $adminCredentials->assignRole('superadmin');

        $adminProfile = new Profile;

        $adminProfile->create([
            'admin_id' => $adminCredentials->id
        ]);
    }
}
