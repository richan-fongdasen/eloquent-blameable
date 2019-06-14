<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\News;

$factory->define(News::class, static function (\Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
