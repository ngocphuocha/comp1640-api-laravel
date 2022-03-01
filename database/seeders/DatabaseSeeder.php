<?php

namespace Database\Seeders;

use App\Models\User;
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
        \App\Models\User::factory(200)->create();
        $this->call([
            DepartmentSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            IdeaSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
