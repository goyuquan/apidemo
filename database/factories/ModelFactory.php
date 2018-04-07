<?php

use Faker\Generator as Faker;


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


$factory->define(App\Shopping_cart::class, function (Faker $faker) {
    return [
        'order_id' => $faker->numberBetween(1, 60),
        'count' => $faker->numberBetween(1, 6),
        'form' => $faker->numberBetween(0, 3),
    ];
});


$factory->define(App\Order_record::class, function (Faker $faker) {
    return [
        'order_id' => $faker->numberBetween(1, 60),
        'price' => $faker->randomFloat(null, 0.5, 100),
    ];
});
