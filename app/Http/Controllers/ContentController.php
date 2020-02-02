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
      $content->user_id=Auth::user()->id;
      $content->save();
      return response()->json([
           'content' => $content,
       ]);
    }

    function index(Request $request){
      $content = new Content;
      $content = DB::table('contents')->join('users', 'users.id', '=', 'contents.user_id')->paginate(15);
      return response()->json([
           'content' => $content,
       ]);
    }
}
