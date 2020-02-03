<?php

namespace App\Http\Controllers;
use App\ContentLike;
use Illuminate\Http\Request;
use DB;
use Auth;
class ContentLikeController extends Controller
{
    function store(Request $request){

      $like = new ContentLike;
      $like->key=$request->key;
      $like->content_id=$request->content_id;

      $like->user_id=Auth::user()->id;
      $like->save();

      return $this ->index($request);
    }

    function index(Request $request){

      $likes = DB::table('content_likes')->select('key', DB::raw('count(*) as total'))->
      where("content_id", "=", $request->content_id)->groupBy("content_id")->groupBy("key")->get();
      return response()->json([
           'likes' => [$request->content_id => $likes],
       ]);
    }
}
