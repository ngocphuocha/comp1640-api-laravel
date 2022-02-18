<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')
            ->insert([
                ['name' => 'category 1', 'description' => 'This is category 1', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'category 2', 'description' => 'This is category 2', 'created_at' => now(), 'updated_at' => now()],
            ]);
    }
}
