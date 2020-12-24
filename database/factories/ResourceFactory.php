<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Resource;
use Faker\Generator as Faker;

$factory->define(Resource::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence($nbWords = 7, $variableNbWords = true),
        'content' => $faker->paragraphs($nb = 5, $asText = true),
        'course_id' => $faker->randomElement($array = array (1, 2, 3, 6))
    ];
});
