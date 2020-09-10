<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\Comment;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->paragraph,
        ];
    }
}
