<?php

use Faker\Generator as Faker;

$factory->define(App\Pensee::class, function (Faker $faker) {
    return [
        'text' => $faker->text,
    ];
});
