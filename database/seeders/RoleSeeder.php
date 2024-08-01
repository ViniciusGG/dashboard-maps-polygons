<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create admin and manager
        Role::create(['guard_name' => 'api', 'name' => 'super_admin']);
        // Role::create(['name' => 'admin']);
        Role::create(['guard_name' => 'api', 'name' => 'admin']);

        Role::create(['guard_name' => 'api', 'name' => 'technicians']);
        Role::create(['guard_name' => 'api', 'name' => 'external_service_provider']);

        //permissions users
        Permission::create(['guard_name' => 'api', 'name' => 'view users']);
        Permission::create(['guard_name' => 'api', 'name' => 'create users']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit users']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete users']);

        //permissions workspace
        Permission::create(['guard_name' => 'api', 'name' => 'view workspaces']);
        Permission::create(['guard_name' => 'api', 'name' => 'create workspaces']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit workspaces']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete workspaces']);

        //permissions members workspace
        Permission::create(['guard_name' => 'api', 'name' => 'view members workspace']);
        Permission::create(['guard_name' => 'api', 'name' => 'create members workspace']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit members workspace']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete members workspace']);

        //permissions alert
        Permission::create(['guard_name' => 'api', 'name' => 'view alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'create alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete alerts']);

        //permissions message alert
        Permission::create(['guard_name' => 'api', 'name' => 'view message alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'create message alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit message alerts']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete message alerts']);

        //permissions message customer support
        Permission::create(['guard_name' => 'api', 'name' => 'view message customer support']);
        Permission::create(['guard_name' => 'api', 'name' => 'create message customer support']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit message customer support']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete message customer support']);

        //workspace video
        Permission::create(['guard_name' => 'api', 'name' => 'view workspace video']);
        Permission::create(['guard_name' => 'api', 'name' => 'create workspace video']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit workspace video']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete workspace video']);

        //workspace area point
        Permission::create(['guard_name' => 'api', 'name' => 'view workspace area point']);
        Permission::create(['guard_name' => 'api', 'name' => 'create workspace area point']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit workspace area point']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete workspace area point']);

        //add permissions admin
        $roleAdmin = Role::findByName('admin', 'api');
        $roleAdmin->givePermissionTo([
            'view members workspace',
            'create members workspace',
            'edit members workspace',
            'delete members workspace',
            'view alerts',
            'delete alerts',
            'create message alerts',
            'view message customer support',
            'create message customer support',
            'view workspace video',
            'create workspace video',
            'edit workspace video',
            'delete workspace video',
        ]);

        $roleTechnicians = Role::findByName('technicians', 'api');
        $roleTechnicians->givePermissionTo([
            'view message alerts',
            'create message alerts',
            'view message customer support',
            'create message customer support',
        ]);

        $roleexternal_service_provider = Role::findByName('external_service_provider', 'api');
        $roleexternal_service_provider->givePermissionTo([
            'view message customer support',
            'create message customer support',
        ]);
    }
}
