<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\Article;

$factory->define(Article::class, static function (\Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
