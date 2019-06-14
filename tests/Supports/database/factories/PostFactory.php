<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\Post;

$factory->define(Post::class, static function (\Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
