<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find(Auth::user()->id);

        if ($request->has('password') && !empty($request->password)) {
            $user->password = $request->password;
        }
        if ($request->email != $user->email && !empty($request->email)) {
            $user->email = $request->email;
        }
        if ($request->has('avatar') && !empty($request->avatar)) {
            $user->avatar = $request->avatar;
        }
        if ($request->has('background') && !empty($request->background)) {
            $user->background = $request->background;
        }
        if ($request->has('bio') && !empty($request->bio)) {
            $user->bio = $request->bio;
        }

        $user->save();

        return response()->json([
         'user' => $user,
         'groups' => $user->groups()->get()
     ]);
    }
}
