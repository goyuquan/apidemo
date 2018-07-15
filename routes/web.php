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


$router->group([ 'prefix' => '/api' ], function ($router) {

    $router->group(['middleware' => ['auth', 'after']], function () use ($router) {
        $router->get('/users/list', 'UserController@index');

        $router->group([ 'prefix' => '/order' ], function ($router) {
            $router->get('/list', 'OrderController@index');
            $router->get('/{id}', 'OrderController@show');
        });

        $router->group([ 'prefix' => '/product' ], function ($router) {
            $router->get('/list', 'ProductController@index');
            $router->get('/{id}', 'ProductController@show');
            $router->post('/edit/{id}', 'ProductController@update');
        });

        $router->group([ 'prefix' => '/setting' ], function ($router) {
            $router->group([ 'prefix' => '/option' ], function ($router) {
                $router->get('/columns', 'SettingController@columns');

                $router->post('/item', 'SettingController@optionCreate');
                $router->delete('/item/{id}', 'SettingController@optionDelete');
                $router->put('/item/{id}', 'SettingController@optionUpdate');
                $router->get('/item/{id}', 'SettingController@optionGet');

                $router->get('/config/{id}', 'SettingController@optionConfig');
            });
            $router->get('/{id}', 'OptionController@show');
            $router->put('/{id}', 'OptionController@update');
            $router->delete('/{id}', 'OptionController@delete');
        });
    });

    $router->group([ 'prefix' => '/auth' ], function ($router) {
        $router->get('/login','UserController@login');
        $router->get('/logout/{id}','UserController@logout');
    });

    $router->group([ 'prefix' => '/user' ], function ($router) {
        $router->post('/', 'UserController@store');
        $router->get('/{id}', 'UserController@show');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    });

});
