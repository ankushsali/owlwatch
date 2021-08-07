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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('user-signup',  ['uses'=>'UsersController@userSignUp']);
    $router->post('create-employee',  ['uses'=>'UsersController@createEmployee']);
    $router->post('update-profile',  ['uses'=>'UsersController@updateProfile']);
    $router->post('get-school-users',  ['uses'=>'UsersController@getSchoolUsers']);
    $router->post('user-login',  ['uses'=>'UsersController@userLogin']);
    $router->post('assign-school',  ['uses'=>'SchoolsController@assignSchool']);
    $router->post('add-school',  ['uses'=>'SchoolsController@addSchool']);
});