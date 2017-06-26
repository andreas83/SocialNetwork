<?php

namespace App\Http\Controllers;

use App\Content;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;


class ContentController extends BaseController 
{
    use ValidatesRequests;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth", ['only' => ["delete", "post"]]);
    }

    public function get(Request $request)
    {
        $content = new Content;
        $content=$content->with("user");
        
        if ($request->has('id')) {
            $content=$content->where("id", "<=", $request->get("id")); 
        }
        if ($request->has('show')) {
            $content=$content->limit($request->get("show"));  
        }
        else
        {
            $content=$content->limit(50);  
        }
        
        if ($request->has('hash')) {
            
        }
        
        $content=$content->orderBy("id", "desc")->get();
        
        return response($content, 200)
                  ->header('Content-Type', "application/json");
    }
    
    public function post(Request $request)
    {
        $validator = $this->validate($request->all(), [
            'data' => 'required|unique:posts',
        ]);

        if ($validator->fails()) {
            return redirect('api/content')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        
        $user = $request->user();
        
        try {

            $content = new Content;
            $content->content = $request->get("data");
            $content->user_id=$user->id;
            $content->save();
            
        } catch (Exception $ex) {
            return response($ex, 500)
                  ->header('Content-Type', "application/json");
        }
        
        
        
        return response(["created" => true, "data" => $request->get("data")], 200)
                  ->header('Content-Type', "application/json");
    }
    
    public function delete($id)
    {
        $user = Auth::user();
        
    }
}
