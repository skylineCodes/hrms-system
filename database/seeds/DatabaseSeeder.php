<?php

use App\Model\Acl\Role;
use App\Model\Admin\Admin;
use App\Model\Admin\Profile;
use App\Model\Acl\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RoleTableSeeder::class);
        // $this->call(PermissionTableSeeder::class);
        // $this->call(AdminTableSeeder::class);

        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) 
        {
            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Data cleared, starting from blank database.");
        }

        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms, 'guard_name' => 'adminapi']);
        }

        $this->command->info('Default Permissions added.');

        // Confirm roles needed
        if ($this->command->confirm('Create Roles for user, default is superadmin and admin? [y|N]', true)) {

            // Ask for roles from input
            $input_roles = $this->command->ask('Enter roles in comma separate format.', 'superadmin,admin');

            // Explode roles
            $roles_array = explode(',', $input_roles);

            // add roles
            foreach($roles_array as $role) {
                $role = Role::firstOrCreate(['name' => trim($role), 'guard_name' => 'adminapi']);

                if( $role->name == 'superadmin' ) {
                    // assign all permissions
                    $role->syncPermissions(Permission::all());
                    $this->command->info('superadmin granted all the permissions');
                } else {
                    // for others by default only read access
                    $role->syncPermissions(Permission::where('name', 'LIKE', 'read_%')->get());
                }

                // create one user for each role
                $this->createUser($role);
            }

            $this->command->info('Roles ' . $input_roles . ' added successfully');

        } else {
            Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'adminapi']);
            $this->command->info('Added only default superadmin role.');
        }
    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $admin = new Admin;

        $adminProfile = new Profile;

        if( $role->name == 'superadmin' ) {
            $superadminCredentials = $admin->create([
                'username' => 'Korede',
                'email' => 'korede@almondcareers.com',
                'password' => bcrypt('almondhrms'),
                'region' => strtoupper('ng')
            ]);
    
            $superadminCredentials->assignRole($role->name);

            $adminProfile->create([
                'admin_id' => $superadminCredentials->id
            ]);

            $this->command->info('Here is your superadmin details to login:');
            $this->command->warn($superadminCredentials->email);
            $this->command->warn('Password is "almondhrms"');
        } elseif ($role->name == 'admin') {
            $adminCredentials = $admin->create([
                'username' => 'Micheal',
                'email' => 'micheal@almondcareers.com',
                'password' => bcrypt('almondhrms'),
                'region' => strtoupper('ng')
            ]);
    
            $adminCredentials->assignRole($role->name);

            $adminProfile->create([
                'admin_id' => $adminCredentials->id
            ]);
            
            $this->command->info('Here is your admin details to login:');
            $this->command->warn($adminCredentials->email);
            $this->command->warn('Password is "almondhrms"');
        }
    }
}
