<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\GroupType::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(\App\Models\Group::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'interval_minutes' => 30,
        'interval_time_start' => $faker->time(),
        'interval_time_end' => $faker->time(),
        'number_of_winners' => 1,
        'finish_exercise_time' => 25
    ];
});