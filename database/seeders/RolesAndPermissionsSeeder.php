<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminRole = Role::create(['name' => 'super admin']);
        Permission::create(['name' => 'users'])->assignRole($superAdminRole);

        $qaManagerRole = Role::create(['name'=> 'quality assurance manager']);
        Permission::create(['name'=>'categories'])->assignRole($qaManagerRole);
        $QACoordinatorRole = Role::create(['name' => 'QA coordinator']);
        Permission::create(['name' => 'staffs'])->assignRole($QACoordinatorRole);

        $staffRole = Role::create(['name'=>'staff']);
        Permission::create(['name' => 'ideas'])->assignRole($staffRole);
    }
}
