<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    public function getUser($id) {
      $user = User::with(['issue_packs' => function ($query) {
        $query->where('public', '=', true);
      }])->find($id);

      unset($user->connections);

      return response()->json($user);
    }

    public function login(Request $request) {
      $profile = $request->input('profile');

      $user = User::where('email', '=', $profile['email'])->first();

      if(!isset($user)) {
        $user = new User();
        $user->name = $profile['name'];
        $user->email = $profile['email'];
        $user->save();
      }

      Auth::login($user);

      unset($user->connections);

      return response()->json($user);
    }

    public function logout() {
      return response()->json(Auth::logout());
    }
}
