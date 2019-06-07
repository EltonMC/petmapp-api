<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('login', 'AuthController@authenticate');
$router->post('users', 'AuthController@store');

$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    $router->get('users', 'UserController@index');
    // $router->get('users/{id}', 'UserController@show');
    $router->put('users', 'UserController@update');

    $router->get('pets', 'PetController@index');
    $router->post('pets', 'PetController@store');
    $router->get('pets/{id}', 'PetController@show');
    $router->put('pets/{id}', 'PetController@update');
    $router->delete('pets/{id}', 'PetController@destroy');

    $router->post('reservations', 'ReservationController@store');
    $router->get('reservations', 'ReservationController@index');
    $router->put('reservations/{id}', 'ReservationController@update');

    $router->post('ratings', 'RatingController@store');
    $router->get('ratings', 'RatingController@index');

    $router->get('petshops', 'PetshopController@index');
    $router->post('petshops', 'PetshopController@store');
    $router->get('petshops/{id}', 'PetshopController@show');
    $router->put('petshops/{id}', 'PetshopController@update');

    $router->post('services', 'ServiceController@store');
    $router->get('services/{id}', 'ServiceController@show');
    $router->put('services/{id}', 'ServiceController@update');

    $router->post('turns', 'TurnController@store');
    $router->get('turns/{id}', 'TurnController@show');
    $router->put('turns/{id}', 'TurnController@update');
});

