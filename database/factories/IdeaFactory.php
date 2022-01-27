<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class IdeaFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $listCategoryID = DB::table('categories')->get()->pluck('id');
        $listUserID = User::all()->pluck('id');
        $listDepartmentId = Department::all()->pluck('id');

        return [
            'title' => $this->faker->name(),
            'content' => $this->faker->text(),
            'category_id' => $this->faker->randomElement($listCategoryID),
            'user_id' => $this->faker->randomElement($listUserID),
            'department_id' => $this->faker->randomElement($listDepartmentId),
            'is_active' => $this->faker->boolean()
        ];
    }
}
