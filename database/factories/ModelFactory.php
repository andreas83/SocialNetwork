<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'auth_tocken' => str_random(10)
    ];
});

$factory->define(App\Content::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->name,
        
    ];
});