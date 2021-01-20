<?php

use App\User;
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

    Route::resource('content', 'ContentController')->only([
      'index',
    ]);
    Route::resource('group', 'GroupController')->only([
      'index',
    ]);
    Route::get('group/membership/{id}', 'GroupController@membership');

    Route::get('content/comments/{id}', 'ContentController@comments');
    Route::resource('content/likes', 'ContentLikeController')->only([
      'index',
    ]);

    Route::get('/user/public', function (Request $request) {
        if ($request->has('id')) {
            return User::where('id', '=', $request->id)->select('id', 'name', 'bio', 'avatar', 'background', 'created_at')->first();
        }
        if ($request->has('name')) {
            return User::where('name', '=', $request->name)->select('id', 'name', 'bio', 'avatar', 'background', 'created_at')->first();
        }
    });
});

Route::post(
    'auth/{provider}',
    'Auth\LoginController@socialLiteLogin'
);
Route::get('auth/{provider}/callback', function () {
    return view('welcome');
})->where('provider', '.*');

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('content/likes', 'ContentLikeController')->only([
      'store',
    ]);

    Route::resource('content', 'ContentController')->only([
      'store', 'update', 'destroy',
    ]);

    Route::resource('group', 'GroupController')->only([
      'store', 'update', 'destroy',
    ]);

    Route::delete('group/membership/{id}', 'GroupController@leave');
    Route::post('group/membership/{id}', 'GroupController@join');

    Route::delete('group/membership/{id}/decline', 'GroupController@declineMember');
    Route::post('group/membership/{id}/approve', 'GroupController@approveMember');


    Route::resource('user', 'UserController')->only([
      'update',
    ]);

    Route::post('content/upload', 'ContentController@upload');
    Route::post('content/ogparser', 'ContentController@parseog');

    Route::get('/user', function (Request $request) {
        return ['user' => $request->user(), 'groups' => $request->user()->groups()->get()];
    });
});
