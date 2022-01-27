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
        // TODO coi lại bảng departments
        Department::insert([
            ['name' => 'Đào tạo', 'description' => 'this is dao tao'],
            ['name' => '23', 'description' => 'this 23 department']
        ]);
    }
}
