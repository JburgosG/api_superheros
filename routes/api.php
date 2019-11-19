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

/* Route Users */
Route::resource('user', 'UserController', ['only' => ['show', 'store', 'update']]);

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');