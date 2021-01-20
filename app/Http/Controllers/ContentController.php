<?php

namespace App\Http\Controllers;

use App\Content;
use App\Group;
use App\Http\Controllers\Helper\RemoteContent;
use App\Http\Requests\ContentDestroyRequest;
use App\Http\Requests\ContentStoreRequest;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class ContentController extends Controller
{
    public function store(ContentStoreRequest $request)
    {
        $validated = $request->validated();

        $content = new Content();
        $content->json_content = json_encode($validated['json_content']);
        $content->html_content = Purify::clean($validated['html_content']);
        $content->anonymous = ($validated['anonymous'] ? 'true' : 'false');
        if($validated['anonymous']==true)
        {
            $content->user_id=(getenv("anonymous") !== false ? getenv("anonymous") : 1 );
        } else {
          $content->user_id = Auth::user()->id;
        }
        $content->visibility = $validated['visibility'];

        $content->has_comment = ($validated['has_comment'] ? 'true' : 'false');
        $content->is_comment = ($validated['is_comment'] ? 'true' : 'false');
        $content->comments = 0;
        $content->parent_id = $validated['parent_id'];

        $content->group_id = ($request->group_id > 0 ? $request->group_id : 0);

        if ($content->group_id > 0) {
            $group = Group::find($content->group_id);
            $group->posts = $group->posts + 1;
            $group->save();
        }


        $content->save();

        if ($request->is_comment) {
            $parent = Content::find($request->parent_id);
            $parent->has_comment = 'true';
            $parent->comments = $parent->comments + 1;
            $parent->save();
        }

        return $this->getContentById($content->id);
    }

    public function getContentById($id)
    {
        $content = DB::table('contents')->
          select('contents.*', 'users.name', 'users.avatar')->
          join('users', 'users.id', '=', 'contents.user_id')->where('contents.id', '=', $id)->get();

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
        $content->json_content = json_encode($validatedData['json_content']);
        $content->html_content = $request->html_content;

        if ($content->user_id == Auth::user()->id) {
            $content->save();
        }

        return $this->getContentById($content->id);
    }

    public function index(Request $request)
    {
        $content = DB::table('contents')
          ->where('is_comment', '=', 'false')
          ->select('contents.*', 'users.name', 'users.avatar')
          ->join('users', 'users.id', '=', 'contents.user_id')
          ->orderBy('contents.id', 'desc');

        if ($request->has('next_id') && $request->next_id > 0) {
            $content->where('contents.id', '<=', $request->next_id);
        }
        if ($request->has('content_id') && $request->content_id > 0) {
            $content->where('contents.id', '=', $request->content_id);
        }
        if ($request->has('user_id') && $request->user_id > 0) {
            $content->where('users.id', '=', $request->user_id);
        }
        if ($request->has('group_id') && $request->group_id > 0) {
            $content->where('contents.group_id', '=', $request->group_id);
        }
        if ($request->has('limit') && $request->limit <= 100) {
            $content->limit($request->limit);
        } else {
            $content->limit(15);
        }

        return response()->json([
           'content' => $content->get(),
       ]);
    }

    /**
     * @ todo check why ContentDestroyRequest does not validates get params
     */
    public function destroy(Request $request, $id)
    {
        $content = Content::find($id);

        if ($content->user_id == Auth::user()->id) {
            if ('true' == $content->is_comment) {
                $parent = Content::find($content->parent_id);

                $parent->comments = $parent->comments - 1;
                if (0 == $parent->comments) {
                    $parent->has_comment = 'false';
                }
                $parent->save();
            }
            $content->destroy($id);
        }
    }

    public function upload(Request $request)
    {
        $i = 0;
        foreach ($request->upload as $upload) {
            $filename[$i] = $upload->store('public');
            $path[$i] = Storage::url($filename[$i]);
            ++$i;
        }

        return response()->json([
            'path' => $path,
        ]);
    }

    public function comments(Request $request, $id)
    {
        $content = DB::table('contents')->
        where('is_comment', '=', 'true')->
        where('parent_id', '=', $id)->
        select('contents.*', 'users.name', 'users.avatar')->
        join('users', 'users.id', '=', 'contents.user_id')->
        orderBy('contents.id', 'desc')->
        paginate(15);

        return response()->json([
           'content' => $content,
       ]);
    }

    public function permalink(Request $request, $id)
    {
        function recursiveFind(array $array, $needle)
        {
            $iterator = new \RecursiveArrayIterator($array);
            $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
            $return = [];
            foreach ($recursive as $key => $value) {
                if ($key === $needle) {
                    $return[] = $value;
                }
            }

            return $return;
        }

        $content = Content::find($id);
        $data = json_decode($content->json_content, true);
        $images = recursiveFind($data, 'src');
        $text = recursiveFind($data, 'text');
        $title = $text[0];
        array_shift($text);
        $text = implode(' ', $text);

        $images = $this->array_values_recursive($images);

        return view('welcome', ['images' => $images, 'title' => $title, 'desc' => $text]);
    }

    /**
     * @todo find another place for this helper functions
     */
    public function array_values_recursive($array)
    {
        $flat = [];

        foreach ($array as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, $this->array_values_recursive($value));
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    public function parseog(Request $request)
    {
        $validatedData = $request->validate([
        'url' => ['required', 'url'],
        ]);

        $page = RemoteContent::fetch($validatedData['url']);

        return response()->json([
         'ogtags' => [
           'description' => $page->description,
           'title' => $page->title,
           'image' => $page->image,
         ],
      ]);
    }
}
