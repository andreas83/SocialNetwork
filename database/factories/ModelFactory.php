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
    
    $data[]=["type" => "video",
             "url" => $faker->url,
             "text" => $faker->text(200),
             "mime" => $faker->mimeType()
            ];
    
    $data[]=["type" => "www",
             "url" => $faker->url, 
             "text" => $faker->text(200),
             "img" => $faker->imageUrl(), 
             "title" => $faker->text(50), 
             "description" => $faker->text()
            ];
    
    $data[]=["type" => "img",
             "img" => $faker->imageUrl(), 
             "text" => $faker->text(200),
             "dimeension" => [$faker->randomDigitNotNull, $faker->randomDigitNotNull], 
            ];
    
    $data[]=["type" => "generic" , 
            "text" => $faker->text(200)
            ];
    return [
        'content' => json_encode($faker->randomElement($data)),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});