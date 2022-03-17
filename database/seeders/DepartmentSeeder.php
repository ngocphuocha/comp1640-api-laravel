<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::insert([
            ['name' => 'Educate', 'description' => 'this is dao tao'],
            ['name' => 'Accountant', 'description' => 'this 23 department']
        ]);
    }
}
