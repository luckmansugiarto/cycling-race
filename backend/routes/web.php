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

$router->group(['middleware' => 'throttle:10,1'], function ($app) {
    $app->get('clubs', 'ClubController@getAll');
    $app->post('clubs', 'ClubController@createNew');
    $app->put('clubs/{id}', 'ClubController@update');

    $app->get('races', 'RaceController@getAll');
    $app->post('races/{id}/riders', 'RaceController@addParticipant');
    $app->post('races/{id}/riders/{riderId}', 'RaceController@saveResult');
    $app->post('races', 'RaceController@createNew');
    $app->put('races/{id}', 'RaceController@update');

    $app->get('riders', 'RiderController@getAll');
    $app->post('riders', 'RiderController@createNew');
});