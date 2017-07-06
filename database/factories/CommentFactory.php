<?php

$commentClass = \RichanFongdasen\EloquentBlameableTest\Models\Comment::class;

$factory->define($commentClass, function (\Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});
