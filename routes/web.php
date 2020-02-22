<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Content;

function recursiveFind(array $array, $needle) {
  $iterator = new RecursiveArrayIterator($array);
  $recursive = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
  $return = [];
  foreach ($recursive as $key => $value) {
    if ($key === $needle) {
      $return[] = $value;
    }
  } 
  return $return;
}

Route::get('/permalink/{id}', function($id){
      $content=Content::find($id);
      $data=json_decode($content->json_content, true);
      $images=recursiveFind($data, "src");
      $text=recursiveFind($data, "text");
      $title=$text[0];
      array_shift($text);
      $text=implode(" ", $text);
      return view('welcome', ["images" => $images, "title"=> $title, "desc" => $text]);
});

Route::get('/{any}', function(){
        return view('welcome');
})->where('any', '.*');
