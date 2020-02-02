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
Route::group(['middleware' => ['api']], function () {
    Auth::routes();
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('content', 'ContentController');
    Route::get("content/comments/{id}", 'ContentController@comments');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
