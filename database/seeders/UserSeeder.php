<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin@gmail.com'),
        ]);
        $superAdmin->assignRole('super admin');
        $superAdmin->givePermissionTo('users');

        $qaManager = User::create([
            'email' => 'qamanager@gmail.com',
            'password' => bcrypt('qamanager@gmail.com'),
        ]);
        $qaManager->assignRole('quality assurance manager');
        $qaManager->givePermissionTo('categories');

        $QACoordinatorRole = User::create([
            'email' => 'qac@gmail.com',
            'password' => bcrypt('qac@gmail.com'),
            'department_id' => 1,
        ]);
        $QACoordinatorRole->assignRole('QA coordinator');
        $QACoordinatorRole->givePermissionTo('staffs');

        $staff = User::create([
            'email' => 'staff@gmail.com',
            'password' => bcrypt('staff@gmail.com'),
            'department_id' => 1
        ]);
        $staff->assignRole('staff');
        $staff->givePermissionTo('ideas');


    }
}
