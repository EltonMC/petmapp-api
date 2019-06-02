<?php

use Illuminate\Support\Facades\Hash;

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


$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'     => $faker->name,
        'email'    => $faker->unique()->email,
        'password' => Hash::make('123qwerty'),
        'gender' => $faker->randomElement($array = array ('male','female','other')),
        'type' => $faker->randomElement($array = array ('client','petshop','admin')),
        'photo' => $faker->imageUrl($width = 640, $height = 480),
    ];
});

$factory->define(App\Address::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'cep' => $faker->postcode,
        'state' => $faker->state,
        'city' => $faker->city,
        'district' => $faker->citySuffix,
        'street' => $faker->streetName,
        'number' => $faker->buildingNumber,
        'complement' => $faker->streetAddress,
        'longitude' => $faker->longitude($min = -180, $max = 180),
        'latitude' => $faker->latitude($min = -90, $max = 90) ,
    ];
});

$factory->define(App\Phone::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'phone' => $faker->e164PhoneNumber,
    ];
});

$factory->define(App\Petshop::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'name'     => $faker->name,
        'logo' => $faker->imageUrl($width = 640, $height = 480),
        'description' => $faker->text,
        'schedule' => $faker->text
    ];
});

$factory->define(App\Pet::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'name' => $faker->name,
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'type' => $faker->word,
        'breed' => $faker->word,
        'gender' => $faker->randomElement($array = array ('male','female')),
        'temper' => $faker->word,
        'castrated' => $faker->word,
        'coat' => $faker->word,
        'observation' => $faker->text
    ];
});

$factory->define(App\PetshopImage::class, function (Faker\Generator $faker) {
    return [
        'petshop_id' => factory('App\Petshop')->create()->id,
        'image' => $faker->imageUrl($width = 1024, $height = 480)
    ];
});

$factory->define(App\Service::class, function (Faker\Generator $faker) {
    return [
        'petshop_id' => factory('App\Petshop')->create()->id,
        'type' => $faker->word,
        'status' => $faker->boolean,
    ];
});

$factory->define(App\Turn::class, function (Faker\Generator $faker) {
    return [
        'service_id' => factory('App\Service')->create()->id,
        'day' => $faker->randomElement($array = array ('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')),
        'time_start' => $faker->time,
        'time_end' => $faker->time,
        'max_reservation' => $faker->numberBetween($min = 1, $max = 50),
        'percent_discount' => $faker->numberBetween($min = 10, $max = 50),
        'status' => $faker->boolean,
    ];
});


$factory->define(App\Reservation::class, function (Faker\Generator $faker) {
    return [
        'turn_id' => factory('App\Turn')->create()->id,
        'pet_id' => factory('App\Pet')->create()->id,
        'reservation_day' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});

$factory->define(App\Rating::class, function (Faker\Generator $faker) {
    return [
        'reservation_id' => factory('App\Reservation')->create()->id,
        'rate' => $faker->buildingNumber,
        'description' => $faker->text,
    ];
});


$factory->define(App\Bill::class, function (Faker\Generator $faker) {
    return [
        'reservation_id' => factory('App\Reservation')->create()->id,
        'status' => $faker->word
    ];
});


$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'product_name' => $faker->word,
        'product_description' => $faker->text,
    ];
});
