<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Acl\{Role, Permission};

class AuthPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:permission {name} {--R|remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissions = $this->generatePermissions();

        // check if its remove
        if( $is_remove = $this->option('remove') ) {
            // remove permission
            if( Permission::where('name', 'LIKE', '%'. $this->getNameArgument())->delete() ) {
                $this->warn('Permissions ' . implode(', ', $permissions) . ' deleted.');
            }  else {
                $this->warn('No permissions for ' . $this->getNameArgument() .' found!');
            }

        } else {
            // create permissions
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'adminapi' ]);
            }

            $this->info('Permissions ' . implode(', ', $permissions) . ' created.');
        }

        // sync role for superadmin
        if( $role = Role::where('name', 'superadmin')->first() ) {
            $role->syncPermissions(Permission::all());
            $this->info('Superadmin permissions synced');
        }
    }

    
    private function generatePermissions()
    {
        $abilities = ['create', 'read', 'update', 'delete'];
        $name = $this->getNameArgument();

        return array_map(function($val) use ($name) {
            return $val . '_'. $name;
        }, $abilities);
    }

    private function getNameArgument()
    {
        return strtolower(str_plural($this->argument('name')));
    }
}
