<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class GroupController extends Controller
{

    public function index(Request $request)
    {
        $groups = DB::table('groups')
          ->where('visibility', '=', 'public');


        if ($request->has('group_id') && $request->group_id > 0) {
            $groups->where('groups.id', '=', $request->group_id);
        }
        if ($request->has('name')) {
            $groups->where('groups.name', '=', $request->name);
        }

        if ($request->has('limit') && $request->limit <= 100) {
            $groups->limit($request->limit);
        } else {
            $groups->limit(15);
        }

        $groups->leftJoin(
          'group_members', function( $join)
           {
             $join->on( 'groups.id', '=', 'group_members.group_id');
             if (Auth::check())
             {
               $join->on( 'group_members.user_id', '=', Auth::id());
             }

           }
         );

        return response()->json([
           'auth' => Auth::id(),
           'groups' => $groups->get(),
       ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
