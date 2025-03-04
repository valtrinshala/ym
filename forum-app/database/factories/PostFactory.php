<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'user_id' => 1,
            'content' => $this->faker->paragraphs(asText: true),
        ];
    }
}
