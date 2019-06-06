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

$router->group(['middleware' => 'client.credentials','prefix' => 'api'], function() use ($router) {

    $router->get('/{user}/todos', 'TodoController@index');
    $router->post('/todos', 'TodoController@store');
    $router->put('/todos/{todo}', 'TodoController@update');
    $router->delete('/todos/{todo}', 'TodoController@destroy');
    
});

$router->post('/api/login', 'UserController@login');
$router->post('/api/register', 'UserController@register');
$router->get('/api/me', 'UserController@details');