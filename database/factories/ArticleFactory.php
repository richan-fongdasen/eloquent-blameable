<?php

$articleClass = \RichanFongdasen\EloquentBlameableTest\Models\Article::class;

$factory->define($articleClass, function (\Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
