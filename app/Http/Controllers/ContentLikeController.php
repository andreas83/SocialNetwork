<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ContentLikeController extends Controller
{
    function store(Request $request){

      $like = new ContentLike;
      $like->key=$request->key;
      $like->content_id=$request->content_id;

      $like->user_id=Auth::user()->id;
      $like->save();
    }

    function index(Request $request){

      $likes = DB::table('content_likes')->select('key', DB::raw('count(*) as total'))->
      where("content_id", "=", $request->content_id)->groupBy("content_id")->groupBy("key")->get();
      return response()->json([
           'likes' => $likes,
       ]);
    }
}
