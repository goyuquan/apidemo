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

$router->group(
    [
        'prefix' => '/users'
    ], function ($router) {
        $router->get('/', 'UserController@index');
        $router->post('/', 'UserController@store');
        $router->get('/{id}', 'UserController@show');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    }
);


$router->group(
	[
	     'prefix' => '/api'
	], function ($router) {
		$router->get('/login','UserController@authenticate');
		$router->get('/logout/{id}','UserController@logout');
		$router->post('/todo','TodoController@store');
		$router->get('/todo', 'TodoController@index');
		$router->get('/todo/{id}', 'TodoController@show');
		$router->put('/todo/{id}', 'TodoController@update');
		$router->delete('/todo/{id}', 'TodoController@destroy');
	}
);
