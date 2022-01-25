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

    }
}
