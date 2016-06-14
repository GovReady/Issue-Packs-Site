<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Connection;

use Auth;
use Crypt;
use Log;

class RedmineController extends Controller
{
    public function getProjects () {
      $user_id = Auth::id();

      $connection = Connection::where('user_id', '=', $user_id)->where('provider', '=', 'redmine')->first();

      if(!$connection) {
        return response()->json('Redmine connection not found.');
      }

      $client = new \GuzzleHttp\Client();
      $url = $connection->url . '/projects.json?key=' . Crypt::decrypt($connection->access_token);

      try {
        $res = $client->request('GET', $url);
      } catch (Exception $e) {
        return response()->json('Error: ' . $e->getMessage());
      }

      return $res->getBody();
    }

    public function getProject($id) {
      $user_id = Auth::id();

      $connection = Connection::where('user_id', '=', $user_id)->where('provider', '=', 'redmine')->first();

      $client = new \GuzzleHttp\Client();
      $url = $connection->url . '/projects/' . $id . '.json?key=' . Crypt::decrypt($connection->access_token);

      $res = $client->request('GET', $url);

      return $res->getBody();
    }
}
