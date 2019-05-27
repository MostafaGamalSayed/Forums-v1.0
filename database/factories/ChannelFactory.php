<?php

use Faker\Generator as Faker;

$factory->define(App\Channel::class, function (Faker $faker) {
    // This variable ti give the 'slug' and 'name' the same fake value
    $name = $faker->unique()->word;
    return [
        'name' => $name,
        'slug' => $name,
    ];
});
