<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/register', 'API\UserController@register');
Route::post('/login', 'API\UserController@login');
Route::post('/log', 'API\UserController@matchLog');
Route::post('/savematch', 'API\UserController@saveMatch');
Route::get('/previousreport/{user_id}', 'API\UserController@matchReport');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
