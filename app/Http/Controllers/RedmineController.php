<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Connection;
use App\IssuePack;

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

    public function installPack($id, Request $request) {
      $user_id = Auth::id();
      $connection = Connection::where('user_id', '=', $user_id)->where('provider', '=', 'redmine')->first();

      $pack_id = $request->input('pack_id');

      $pack = IssuePack::find($pack_id);

      $client = new \GuzzleHttp\Client();
      $url = $connection->url . '/issues.json?key=' . Crypt::decrypt($connection->access_token);

      foreach($pack->issues as $issue) {
        $payload = [
          'project_id' => (int)$id,
          'subject' => $issue->title,
          'description' => $issue->body
        ];

        $res = $client->request('POST', $url, [
          'json' => [
            'issue' => $payload
          ]
        ]);
      }

      return response()->json('Pack saved successfully');
    }
}
