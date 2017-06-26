<?php

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

$app->get('/', "WebController@index");

$app->get('api/content', "ContentController@get");
$app->post('api/content', "ContentController@post");
$app->delete('api/content', ['middleware' => 'auth', "ContentController@delete"]);

$app->get('api/content/parser', "ContentParserController@get");
