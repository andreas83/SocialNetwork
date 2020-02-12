<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
class UserController extends Controller
{
  function update(Request $request, $id){




    $user = User::find(Auth::user()->id);

    if($request->has("password")  && !empty($request->password))
    {
      $user->password=$request->password;
    }
    if($request->email!=$user->email && !empty($request->email))
    {
      $user->email=$request->email;
    }
    if($request->has("avatar") && !empty($request->avatar) )
    {
      $user->avatar=$request->avatar;
    }
    if($request->has("bio") && !empty($request->bio) )
    {
      $user->bio=$request->bio;
    }



    $user->save();



    return response()->json([
         'user' => $user,
     ]);
  }
}
