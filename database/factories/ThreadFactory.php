<?php

use Faker\Generator as Faker;
use App\User;
use App\Channel;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'channel_id' => function(){
            return factory(Channel::class)->create()->id;
        }
    ];
});
