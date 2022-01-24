<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use App\Http\Controllers\UserController;
use App\Http\Controllers\BulletinController;

$router->get('/', function () use ($router) {
    return config("app.name") . ' running on ' . config('app.env') . '.' . PHP_EOL;
});

$router->group(['prefix' => 'api'], function() use ($router) {
    $controller = new \App\Http\Controllers\ApiController();

    $router->get('/action/{param}', function ($param) use ($controller) {
        return $controller->action($param);
    });
});

$router->group(['prefix' => 'user'], function() use ($router) {

    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@index');

});

$router->group(['prefix' => 'bulletin', 'middleware' => 'auth'], function() use ($router) {
    $router->get('/', 'BulletinController@index');
    $router->post('/', 'BulletinController@index');
});
