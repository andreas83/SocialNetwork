<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\GroupMembers;
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
        if ($request->has('search')) {
            $groups->where('groups.name', 'like', "%".$request->search."%")->orwhere('groups.description', 'like', "%".$request->search."%");
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
        $group=new Group();
        $group->name=$request->name;
        $group->description=$request->description;
        $group->avatar=$request->avatar;
        $group->background="";
        $group->save();


        $members=new GroupMembers();
        $members->user_id=Auth::user()->id;
        $members->group_id=$group->id;
        $members->status="confirmed";
        $members->is_moderator=1;
        $members->save();
        return response()->json([

           'groups' => $group,
       ]);
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
