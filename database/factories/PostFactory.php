<?php

$postClass = \RichanFongdasen\EloquentBlameableTest\Models\Post::class;

$factory->define($postClass, function (\Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
