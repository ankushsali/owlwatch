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
    $router->post('upload-student-image',  ['uses'=>'UsersController@uploadStudentImage']);
    $router->post('get-student-image',  ['uses'=>'UsersController@getStudentImage']);
    /* UsersController APIs End */

    /* SchoolsController APIs Start */
    $router->post('assign-school',  ['uses'=>'SchoolsController@assignSchool']);
    $router->post('add-school',  ['uses'=>'SchoolsController@addSchool']);
    $router->post('update-school',  ['uses'=>'SchoolsController@updateSchool']);
    $router->post('add-location',  ['uses'=>'SchoolsController@addLocation']);
    $router->post('update-location',  ['uses'=>'SchoolsController@updateLocation']);
    $router->post('get-all-locations',  ['uses'=>'SchoolsController@getAllLocations']);
    $router->post('delete-location',  ['uses'=>'SchoolsController@deleteLocation']);
    $router->post('add-duration',  ['uses'=>'SchoolsController@addDuration']);
    $router->post('update-duration',  ['uses'=>'SchoolsController@updateDuration']);
    $router->post('get-all-durations',  ['uses'=>'SchoolsController@getAllDurations']);
    $router->post('delete-duration',  ['uses'=>'SchoolsController@deleteDuration']);
    $router->post('set-default-duration',  ['uses'=>'SchoolsController@setDefaultDuration']);
    $router->post('create-semester',  ['uses'=>'SchoolsController@createSemester']);
    $router->post('update-semester',  ['uses'=>'SchoolsController@updateSemester']);
    $router->post('get-school-semesters',  ['uses'=>'SchoolsController@getSchoolSemesters']);
    $router->post('add-detention-reason',  ['uses'=>'SchoolsController@addDetentionReason']);
    $router->post('create-detention-reason',  ['uses'=>'SchoolsController@createDetentionReason']);
    $router->post('get-detention-reasons',  ['uses'=>'SchoolsController@getDetentionReasons']);
    $router->get('tardy-regular-report',  ['uses'=>'SchoolsController@tardyRegularReport']);
    $router->get('tardy-grouped-report',  ['uses'=>'SchoolsController@tardyGroupedReport']);
    $router->get('detention-regular-report',  ['uses'=>'SchoolsController@detentionRegularReport']);
    $router->get('detention-grouped-report',  ['uses'=>'SchoolsController@detentionGroupedReport']);
    $router->post('all-schools',  ['uses'=>'SchoolsController@allSchools']);
    $router->post('user-schools',  ['uses'=>'SchoolsController@userSchools']);
    $router->post('without-user-schools',  ['uses'=>'SchoolsController@withoutUserSchools']);
    $router->post('unassign-school',  ['uses'=>'SchoolsController@unassignSchool']);
    $router->post('get-school',  ['uses'=>'SchoolsController@getSchool']);
    /* SchoolsController APIs End */

    /* StudentsController APIs Start */
    $router->post('import-student-contacts',  ['uses'=>'StudentsController@importStudentContacts']);
    $router->post('import-student-data',  ['uses'=>'StudentsController@importStudentData']);
    $router->post('import-student-schedules',  ['uses'=>'StudentsController@importStudentSchedules']);
    $router->post('get-student-contacts',  ['uses'=>'StudentsController@getStudentContacts']);
    $router->post('get-student-data',  ['uses'=>'StudentsController@getStudentData']);
    $router->post('get-student-schedules',  ['uses'=>'StudentsController@getStudentSchedules']);
    $router->post('get-single-student',  ['uses'=>'StudentsController@getSingleStudent']);
    $router->post('create-hall-pass',  ['uses'=>'StudentsController@createHallPass']);
    $router->post('get-all-hall-passes',  ['uses'=>'StudentsController@getAllHallPasses']);
    $router->post('expire-hall-pass',  ['uses'=>'StudentsController@expireHallPass']);
    $router->post('get-periods',  ['uses'=>'StudentsController@getPeriods']);
    $router->post('create-tardy',  ['uses'=>'StudentsController@createTardy']);
    $router->post('get-period-tardy',  ['uses'=>'StudentsController@getPeriodTardy']);
    $router->post('tardy-chart-data',  ['uses'=>'StudentsController@tardyChartData']);
    $router->post('update-tardy-excuse',  ['uses'=>'StudentsController@updateTardyExcuse']);
    $router->post('create-detention',  ['uses'=>'StudentsController@createDetention']);
    $router->post('get-detentions',  ['uses'=>'StudentsController@getDetentions']);
    $router->post('update-detention-serve',  ['uses'=>'StudentsController@updateDetentionServe']);
    /* StudentsController APIs End */

    /* SettingsController APIs Start */
    $router->post('update-tardy-setting',  ['uses'=>'SettingsController@updateTardySetting']);
    $router->post('get-setting',  ['uses'=>'SettingsController@getSetting']);
    /* SettingsController APIs End */
});