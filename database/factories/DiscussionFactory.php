<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Discussion;
use Faker\Generator as Faker;

$factory->define(Discussion::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence($nbWords = 7, $variableNbWords = true),
        'content' => $faker->paragraphs($nb = 3, $asText = true),
        'user_id' => $faker->randomElement($array = array (2, 3, 5, 6, 7, 8)),
        'class_id' => 1,
        'course_id' => $faker->randomElement($array = array (null, 1)),
    ];
});
