<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ContentStoreRequest;
use App\Http\Requests\ContentDestroyRequest;

use App\Http\Controllers\Helper\RemoteContent;
use JonnyW\PhantomJs\Client;

class ContentController extends Controller
{
    public function store(ContentStoreRequest $request)
    {
        $validated = $request->validated();

        $content = new Content;
        $content->json_content=json_encode($validated['json_content']);
        $content->html_content=$validated['html_content'];
        $content->anonymous=$validated['anonymous'];
        $content->visibility=$validated['visibility'];

        $content->has_comment=($validated['has_comment']  ? "true" : "false");
        $content->is_comment=($validated['is_comment']  ? "true" : "false");
        $content->parrent_id=$validated['parrent_id'];

        $content->user_id=Auth::user()->id;
        $content->save();

        if ($request->is_comment) {
            $parrent=  Content::find($request->parrent_id);
            $parrent->has_comment="true";
            $parrent->save();
        }



        return $this->getContentById($content->id);
    }

    public function getContentById($id)
    {
        $content = DB::table('contents')->
          select('contents.*', 'users.name', 'users.avatar')->
          join('users', 'users.id', '=', 'contents.user_id')->where("contents.id", "=", $id)->get();
        return response()->json([
         'content' => $content[0],
     ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
        'html_content' => ['required'],
        'json_content' => ['required'],

        ]);

        $content = Content::find($id);
        $content->json_content=json_encode($validatedData['json_content']);
        $content->html_content=$request->html_content;

        if ($content->user_id == Auth::user()->id) {
            $content->save();
        }

        return $this->getContentById($content->id);
    }

    public function index(Request $request)
    {
        $content = DB::table('contents')->where("is_comment", "=", "false")->select('contents.*', 'users.name', 'users.avatar')->join('users', 'users.id', '=', 'contents.user_id')->orderBy("contents.id", "desc")->paginate(15);
        return response()->json([
           'content' => $content,
       ]);
    }

    public function destroy(ContentDestroyRequest $request, $id)
    {
        $content=  Content::find($id);
        if ($content->user_id==Auth::user()->id) {
            $content->destroy($id);
        }
    }

    public function upload(Request $request)
    {
        $i=0;
        foreach ($request->upload as $upload) {
            $filename[$i] = $upload->store('public');
            $path[$i]=Storage::url($filename[$i]);
            $i++;
        }
        return response()->json([

            'path' => $path,
        ]);
    }

    public function comments(Request $request, $id)
    {
        $content = DB::table('contents')->
        where("is_comment", "=", "true")->
        where("parrent_id", "=", $id)->
        select('contents.*', 'users.name', 'users.avatar')->
        join('users', 'users.id', '=', 'contents.user_id')->
        orderBy("contents.id", "desc")->
        paginate(15);
        return response()->json([
           'content' => $content,
       ]);
    }

    public function parseog(Request $request)
    {
        $validatedData = $request->validate([
        'url' => ['required', 'url'],
        ]);

        $page=RemoteContent::fetch($validatedDat['url']);

        return response()->json([
         'ogtags' => [
           "description" => $page->description,
           "title" => $page->title,
           "image" => $page->image
         ]
      ]);
    }
}
