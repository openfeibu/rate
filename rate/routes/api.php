<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function($router) {
    $router->post('login', 'Api\AuthenticateController@login');
    $router->group(['middleware' => ['api']], function($router) {
        $router->get('me', 'Api\AuthenticateController@me');
        $router->get('rates', 'Api\RateController@rates');
    });
});
