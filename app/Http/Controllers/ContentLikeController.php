<?php

namespace App\Http\Controllers;

use App\ContentLike;
use App\Http\Requests\LikeStoreRequest;
use Auth;
use DB;
use Illuminate\Http\Request;

class ContentLikeController extends Controller
{
    public function store(LikeStoreRequest $request)
    {
        $like = new ContentLike();
        $like->key = $request->key;
        $like->content_id = $request->content_id;

        $like->user_id = Auth::user()->id;
        $like->save();

        return $this->index($request);
    }

    public function index(Request $request)
    {
        $likes = DB::table('content_likes')->select('key', DB::raw('count(*) as total'))->
      where('content_id', '=', $request->content_id)->groupBy('content_id')->groupBy('key')->get();

        return response()->json([
           'likes' => ['content_id' => $request->content_id, 'likes' => $likes],
       ]);
    }
}
