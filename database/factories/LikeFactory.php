<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Like::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
    ];
});
