<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupMembers;
use Auth;
use DB;
use Illuminate\Http\Request;

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
            $groups->where('groups.name', 'like', '%'.$request->search.'%')->orwhere('groups.description', 'like', '%'.$request->search.'%');
        }

        if ($request->has('limit') && $request->limit <= 100) {
            $groups->limit($request->limit);
        } else {
            $groups->limit(15);
        }
        if ($request->has('random')) {
            $groups->inRandomOrder();
        }

        return response()->json([
           'groups' => $groups->get(),
       ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->avatar = $request->avatar;
        $group->visibility = $request->visibility;
        $group->background = '';
        $group->posts = 0;
        $group->members = 1;
        $group->save();

        $members = new GroupMembers();
        $members->user_id = Auth::user()->id;
        $members->group_id = $group->id;
        $members->status = 'confirmed';
        $members->is_moderator = 1;
        $members->save();

        return response()->json([
           'groups' => $group,
       ]);
    }

    public function join(Request $request, $id)
    {



        $membership = new GroupMembers();
        $membership->user_id = Auth::user()->id;
        $membership->group_id = $id;
        $group = Group::find($id); 
        if($group->visibility=="public")
        {
            $membership->status = 'confirmed';

            $group->members = $group->members + 1;
            $group->save();
        }else {
            $membership->status = 'awaiting';
        }

        $membership->is_moderator = 0;
        $membership->save();



        return response()->json([
           'membership' => $membership,
       ]);
    }

    public function approveMember(Request $request, $group_id)
    {

        if($this->isModerator($group_id))
        {


            $membership = GroupMembers::where([
                ['group_id', '=', $group_id],
                ['user_id', '=',  $request->user_id]
            ])->first();

            $membership->status = 'confirmed';
            $membership->save();
        }

        return response()->json([
           'membership' => $membership,
       ]);
    }

    public function declineMember(Request $request, $group_id)
    {

        if($this->isModerator($group_id))
        {


            $membership = GroupMembers::where([
                ['group_id', '=', $group_id],
                ['user_id', '=',  $request->user_id]
            ])->delete();

        }

        return response()->json([
           'membership' => $membership,
       ]);
    }

    public function isModerator($group_id, $user_id=0){
        if($user_id==0)
        {
          $user_id=Auth::user()->id;
        }
        $isModerator = DB::table('group_members')->where([
            ['group_id', '=', $group_id],
            ['is_moderator', '=', 1],
            ['user_id', '=', $user_id],
        ])->get()->count();

        if($isModerator==0)
        {
          return false;
        }
        else {
          return true;
        }
    }


    public function leave(Request $request, $id)
    {
        $membership = DB::table('group_members')->where([
            ['group_id', '=', $id],
            ['user_id', '=', Auth::user()->id],
        ])->delete();

        $group = Group::find($id);
        $group->members = $group->members - 1;
        $group->save();

        return response()->json([
           'membership' => $membership,
       ]);
    }

    public function membership(Request $request, $id)
    {
        $moderators = DB::table('group_members')->where([
          ['group_id', '=', $id],
          ['is_moderator', '=', 1],
      ])->select('users.id', 'name', 'avatar')->
      join('users', 'users.id', '=', 'group_members.user_id')->get();

        $awaiting = DB::table('group_members')->where([
          ['group_id', '=', $id],
          ['status', '=', 'awaiting'],
      ])->select('users.id', 'name', 'avatar')->
      join('users', 'users.id', '=', 'group_members.user_id')->get();

        $members = DB::table('group_members')->where([
          ['group_id', '=', $id],
          ['status', '=', 'confirmed'],
      ])->select('users.id', 'name', 'avatar')->
      join('users', 'users.id', '=', 'group_members.user_id')->get();

        return response()->json([
           'moderators' => $moderators,
           'pending' => $awaiting,
           'members' => $members,
       ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $group = Group::find($id);

      if ($request->has('avatar') && !empty($request->avatar)) {
          $group->avatar = $request->avatar;
      }
      if ($request->has('background') && !empty($request->background)) {
          $group->background = $request->background;
      }
      if ($request->has('description') && !empty($request->description)) {
          $group->description = $request->description;
      }

      if($this->isModerator($id))
      {
          $group->save();
      }

      return response()->json([
       'group' => $group
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
