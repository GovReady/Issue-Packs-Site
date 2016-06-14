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

      //Decrypt the access tokens
      foreach($connections as $connection) {
        $connection->access_token = Crypt::decrypt($connection->access_token);
      }

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
        'provider' => $input['provider']
      ]);

      if(isset($input['url'])) {
        $connection->url = $input['url'];
      }

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

      $connection->save();

      return response()->json($connection);
    }

    public function deleteConnection ($id) {
      $user_id = Auth::id();

      $connection = Connection::find($id);

      if($connection->user_id !== $user_id) {
        return response()->json('You are not authorized to delete that connection', 401);
      }

      return response()->json($connection->delete());
    }
}
