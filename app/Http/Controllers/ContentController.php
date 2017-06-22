<?php

namespace App\Http\Controllers;

use App\Content;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class ContentController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth", ['only' => ["delete"]]);
    }

    public function get(Request $request)
    {
        $content = new Content;
        if ($request->has('id')) {
            $content=$content->where("id", "<=", $request->get("id")); 
        }
        if ($request->has('show')) {
            $content=$content->limit($request->get("show"));  
        }
        if ($request->has('hash')) {
            
        }
        
        
        $content=$content->get();
        
        return response($content, 200)
                  ->header('Content-Type', "application/json");
    }
    
    public function post(Request $request)
    {
        if (!$request->has('data')) {
            $content = array("status" => "Parameter data is missing");
            return response($content, 500)
                  ->header('Content-Type', "application/json");
        }
        
        if ($request->has('preview')) {
            
        }
    }
    
    public function delete($id)
    {
        $user = Auth::user();
        
    }
}
