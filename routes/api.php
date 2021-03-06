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

Route::post('/register', 'Api\UserController@register');
Route::post('/login', 'Api\UserController@login')->name('login');


Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::resource('/record', 'Api\RecordController');
    Route::post('/expected-daily-calories', 'Api\UserController@expectedDailyCalories');
});




//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
