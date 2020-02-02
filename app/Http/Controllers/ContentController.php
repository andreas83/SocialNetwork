<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use Auth;
use DB;
class ContentController extends Controller
{
    function store(Request $request)
    {

      $content = new Content;
      $content->json_content=json_encode($request->json_content);
      $content->html_content=$request->html_content;
      $content->anonymous=$request->anonymous;
      $content->visibility=$request->visibility;

      $content->has_comment=($request->has_comment ? "true" : "false");
      $content->is_comment=($request->is_comment ? "true" : "false");
      $content->parrent_id=$request->parrent_id;

      $content->user_id=Auth::user()->id;
      $content->save();

      if($request->is_comment)
      {
        $parrent=  Content::find($request->parrent_id);
        $parrent->has_comment="true";
        $parrent->save();
      }

      return response()->json([
           'content' => $content,
       ]);
    }

    function index(Request $request){
      $content = new Content;
      $content = DB::table('contents')->where("is_comment", "=", "false")->select('contents.*', 'users.name')->join('users', 'users.id', '=', 'contents.user_id')->orderBy("contents.id", "desc")->paginate(15);
      return response()->json([
           'content' => $content,
       ]);
    }

    function comments(Request $request, $id){
      $content = new Content;
      $content = DB::table('contents')->
      where("is_comment", "=", "true")->
      where("parrent_id", "=", $id)->
        select('contents.*', 'users.name')->
        join('users', 'users.id', '=', 'contents.user_id')->
        orderBy("contents.id", "desc")->
        paginate(15);
      return response()->json([
           'content' => $content,
       ]);
    }


}
