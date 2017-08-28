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

Route::middleware(['auth.basic.once'])->group(function () {
    Route::resource('users', 'UserController', ['only' => [
        'index', 'show', 'store'
    ]]);
    Route::resource('shifts', 'ShiftController', ['only' => [
        'index', 'store', 'update'
    ]]);
    Route::get('shiftmates', 'ShiftController@get_shiftmates');
    Route::get('hours', 'ShiftController@get_hours');
});