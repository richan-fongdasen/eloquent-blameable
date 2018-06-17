<?php

$adminClass = \RichanFongdasen\EloquentBlameableTest\Models\Admin::class;

$factory->define($adminClass, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(12)),
        'remember_token' => str_random(12),
    ];
});
