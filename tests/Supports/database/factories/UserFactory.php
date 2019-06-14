<?php

use RichanFongdasen\EloquentBlameableTest\Supports\Models\User;

$factory->define(User::class, static function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(12)),
        'remember_token' => str_random(12),
    ];
});
