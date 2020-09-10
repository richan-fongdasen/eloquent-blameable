<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'   => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}
