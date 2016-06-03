<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    public function login(Request $request) {
      $profile = json_decode($request->input('profile'));

      $user = User::where('email', '=', $profile->email)->first();

      if(!isset($user)) {
        $user = new User();
        $user->name = $profile->name;
        $user->email = $profile->email;
        $user->save();
      }

      return response()->json(Auth::login($user));
    }

    public function logout() {
      return response()->json(Auth::logout());
    }
}
