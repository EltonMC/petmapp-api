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

//product routes
$router->get('products', 'ProductsController@index');
$router->get('products/{id}', 'ProductsController@show');
$router->put('products/{id}', 'ProductsController@update');
$router->post('products', 'ProductsController@store');
$router->delete('products/{id}', 'ProductsController@destroy');

$router->post('login', 'AuthController@authenticate');

$router->post('users', 'UserController@store');

$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    $router->get('users/{id}', 'UserController@show');
    $router->put('users/{id}', 'UserController@update');

    $router->get('pets', 'PetController@index');
    $router->post('pets', 'PetController@store');
    $router->get('pets/{id}', 'PetController@show');
    $router->put('pets/{id}', 'PetController@update');
    $router->delete('pets/{id}', 'PetController@destroy');

    $router->post('reservations', 'ReservationController@store');
    $router->get('reservations', 'ReservationController@index');
    $router->put('reservations/{id}', 'ReservationController@update');

});
