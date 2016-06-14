<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Crypt;

use App\Http\Requests;
use App\Connection;

class ConnectionController extends Controller
{
    public function getConnections() {
      $user_id = Auth::id();

      $connections = Connection::where('user_id', '=', $user_id)->get();

      return response()->json($connections);
    }

    public function saveConnection(Request $request) {
      $input = $request->all();

      if(!isset($input['provider'])) {
        return response()->json('No provider found.', 400);
      }

      $user_id = Auth::id();

      $connection = Connection::firstOrNew([
        'user_id' => $user_id,
        'provider' => ''
      ]);

      if(isset($input['access_token'])) {
        $connection->access_token = Crypt::encrypt($input['access_token']);
      }

      if(isset($input['connection'])) {
        $connection->connection = $input['connection'];
      }

      if(isset($input['isSocial'])) {
        $connection = $input['isSocial'];
      }

      if(isset($input['auth_user_id'])) {
        $connection->auth_user_id = $input['auth_user_id'];
      }

      return response()->json($connection->save());
    }
}
