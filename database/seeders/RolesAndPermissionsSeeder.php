<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        Role::insert([
            ['name' => 'super admin', 'guard_name' => 'web'],
            ['name' => 'quality assurance manager', 'guard_name' => 'web'],
            ['name' => 'QA coordinator', 'guard_name' => 'web'],
            ['name' => 'staff', 'guard_name' => 'web'],
        ]);
        $permissionsByRole = [
            'super admin' => ['users.create', 'users.show', 'users.edit', 'users.delete'],
            'quality assurance manager' => ['categories.create', 'categories.show', 'categories.edit', 'categories.delete'],
            'QA coordinator' => ['staffs'],
            'staff' => ['ideas.create', 'ideas.show', 'ideas.edit', 'ideas.delete'],
        ];

        $insertPermissions = fn($role) => collect($permissionsByRole[$role])
            ->map(fn($name) => DB::table('permissions')->insertGetId(['name' => $name, 'guard_name' => 'web']))
            ->toArray();

        $permissionIdsByRole = [
            'super admin' => $insertPermissions('super admin'),
            'quality assurance manager' => $insertPermissions('quality assurance manager'),
            'QA coordinator' => $insertPermissions('QA coordinator'),
            'staff' => $insertPermissions('staff'),
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $role = Role::whereName($role)->first();

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn($id) => [
                        'role_id' => $role->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }

//        $superAdminRole = Role::create(['name' => 'super admin']);
//        Permission::create(['name' => 'users'])->assignRole($superAdminRole);
//        Permission::create(['name' => 'all'])->assignRole($superAdminRole);
//
//        $qaManagerRole = Role::create(['name'=> 'quality assurance manager']);
//        Permission::create(['name'=>'categories'])->assignRole($qaManagerRole);
//
//        $QACoordinatorRole = Role::create(['name' => 'QA coordinator']);
//        Permission::create(['name' => 'staffs'])->assignRole($QACoordinatorRole);
//
//        $staffRole = Role::create(['name'=>'staff']);
//        Permission::create(['name' => 'ideas'])->assignRole($staffRole);
    }
}
