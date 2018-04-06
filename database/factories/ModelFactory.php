<?php

use Faker\Generator as Faker;


$factory->define(App\User::class, function (Faker $faker) {
    return [
        'contact_id' => $faker->randomNumber(10),
        'phone' => $faker->numberBetween(10000000000, 19999999999),
        'password' => '$2y$10$sr6a1PMteAvWvGZMdmhTPu7m3G9SAj/2uBRovZ3ZiEDwsVPtxs3Vu', //123123
        'name' => $faker->name,
        'role' => $faker->randomNumber(10),
    ];
});


$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(0, 60),
        'phone' => $faker->numberBetween(10000000000, 19999999999),
        'name' => $faker->name,
        'address' => $faker->address,
        'default' => $faker->boolean,
    ];
});
