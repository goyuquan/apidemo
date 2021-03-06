<?php

use Faker\Generator as Faker;


$factory->define(App\Option::class, function (Faker $faker) {
    return [
        'column' => $faker->randomElement(['option1', 'option2', 'option3']),
        'option' => $faker->word,
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'phone' => $faker->numberBetween(10000000000, 19999999999),
        'password' => '$2y$10$sr6a1PMteAvWvGZMdmhTPu7m3G9SAj/2uBRovZ3ZiEDwsVPtxs3Vu', //123123
        'name' => $faker->name,
        'role' => $faker->randomNumber(1),
    ];
});


$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'phone' => $faker->numberBetween(10000000000, 19999999999),
        'name' => $faker->name,
        'address' => $faker->address,
        'default' => 0,
    ];
});


$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'contact_id' => function () {
            return factory(App\Contact::class)->create()->id;
        },
        'status' => $faker->randomNumber(1),
        'period' => $faker->city,
        'delivery_time' => $faker->time,
    ];
});


$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'status' => $faker->randomNumber(1),
        'name' => $faker->name,
        'price' => $faker->randomFloat(null, 0.1, 10),
        'unit' => $faker->lexify('??'),
        'origin' => $faker->city,
        'describe' => $faker->text,
    ];
});
