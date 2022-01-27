<?php

namespace Database\Factories;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $listUserId = User::all()->pluck('id');
        $listIdeaId = Idea::all()->pluck('id');

        return [
            'content' => $this->faker->text(),
            'user_id' => $this->faker->randomElement($listUserId),
            'idea_id' => $this->faker->randomElement($listIdeaId)
        ];
    }
}
