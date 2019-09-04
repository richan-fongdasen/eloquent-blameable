<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\User;

$factory->define(User::class, static function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(Str::random(12)),
        'remember_token' => Str::random(12),
    ];
});
