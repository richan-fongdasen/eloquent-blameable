<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\Comment;

$factory->define(Comment::class, static function (\Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});
