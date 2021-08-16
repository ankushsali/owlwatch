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
    /* UsersController APIs Start */
    $router->post('user-signup',  ['uses'=>'UsersController@userSignUp']);
    $router->post('create-employee',  ['uses'=>'UsersController@createEmployee']);
    $router->post('update-profile',  ['uses'=>'UsersController@updateProfile']);
    $router->post('get-school-users',  ['uses'=>'UsersController@getSchoolUsers']);
    $router->post('update-permission',  ['uses'=>'UsersController@updatePermission']);
    $router->post('revoke-user',  ['uses'=>'UsersController@revokeUser']);
    $router->post('user-login',  ['uses'=>'UsersController@userLogin']);
    /* UsersController APIs End */

    /* SchoolsController APIs Start */
    $router->post('assign-school',  ['uses'=>'SchoolsController@assignSchool']);
    $router->post('add-school',  ['uses'=>'SchoolsController@addSchool']);
    /* SchoolsController APIs End */

    /* StudentsController APIs Start */
    $router->post('import-student-contacts',  ['uses'=>'StudentsController@importStudentContacts']);
    $router->post('import-student-data',  ['uses'=>'StudentsController@importStudentData']);
    $router->post('import-student-schedules',  ['uses'=>'StudentsController@importStudentSchedules']);
    $router->post('get-student-contacts',  ['uses'=>'StudentsController@getStudentContacts']);
    $router->post('get-student-data',  ['uses'=>'StudentsController@getStudentData']);
    $router->post('get-student-schedules',  ['uses'=>'StudentsController@getStudentSchedules']);
    $router->post('get-single-student',  ['uses'=>'StudentsController@getSingleStudent']);
    /* StudentsController APIs End */
});