<?php

use Faker\Generator as Faker;

use Ramsey\Uuid\Uuid;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3, 1),
        'comment' => $faker->sentence(10, 5),
        'uuid' => Uuid::uuid4()->toString(),
    ];
});
