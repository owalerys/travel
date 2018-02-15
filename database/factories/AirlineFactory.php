<?php

use Faker\Generator as Faker;

$factory->define(\App\Airline::class, function (Faker $faker) {
    return [
        'iata_code' => strtoupper(str_random(2)),
        'icao_code' => strtoupper(str_random(3)),
        'name' => $faker->unique()->company . ' Airlines',
        'country_code' => $faker->countryCode
    ];
});
