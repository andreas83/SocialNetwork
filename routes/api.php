<?php

use Illuminate\Http\Request;
use App\User;
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
    Route::resource('content/likes', 'ContentLikeController')->only([
      'store','index'
    ]);
    Route::resource('content', 'ContentController');

    Route::resource('user', 'UserController')->only([
      'update'
    ]);

    Route::get("content/comments/{id}", 'ContentController@comments');
    Route::post("content/upload", 'ContentController@upload');
});
Route::middleware('auth:api')->get('/user/{name}', function (Request $request) {
      return User::where("name" , '=', $request->name)->select("id", "name", "created_at")->first();

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
