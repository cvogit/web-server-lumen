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

$router->get('/', function () {
    return "Hello";
});

$router->post('register', 'RegisterController@register');

$router->get('login', 		'AuthController@login');


// JWT protected routes
$router->group(['middleware' => ['jwt', 'user']], function () use ($router) {

	$router->get('refresh', 		'AuthController@refresh');

});