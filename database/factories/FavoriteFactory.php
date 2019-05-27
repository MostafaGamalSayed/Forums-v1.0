<?php

use Faker\Generator as Faker;
use App\Favorite;

$factory->define(Favorite::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory('App\user')->create()->id;
        },
        'favoritable_id'
    ];
});
