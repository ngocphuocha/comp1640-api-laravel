<?php

namespace Database\Factories;

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
        $categoriesId = DB::table('categories')->get()->pluck('id');
//        dd($categoriesId);
        return [
            'title' => $this->faker->name(),
            'content' => $this->faker->text(),
            'category_id' => $this->faker->randomElement($categoriesId),
            'is_active' => $this->faker->boolean()
        ];
    }
}
