<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Log;

use App\Http\Requests;
use App\Issue;
use App\IssuePack;
use App\Label;
use App\PackSync;

class PackController extends Controller
{
    public function getMyPacks() {
      $user_id = Auth::id();

      $packs = IssuePack::where('user_id', '=', $user_id)->get();

      foreach($packs as $pack) {
        foreach($pack->issues as $issue) {
          $issue->labels = $issue->labels->toArray();
        }
      }

      return $packs;
    }

    public function createPack(Request $request) {
      $pack = $request->input('pack');
      $user_id = Auth::id();

      $newPack = new IssuePack();
      $newPack->name = $pack['name'];
      $newPack->user_id = $user_id;
      $newPack->save();

      foreach($pack['issues'] as $issue) {
        $newIssue = new Issue();
        $newIssue->title = $issue['title'];
        $newIssue->body = $issue['body'];
        $newIssue->save();

        if(isset($issue['labels'])) {
          foreach($issue['labels'] as $label) {
            $newLabel = new Label();
            $newLabel->name = $label;
            $newLabel->save();

            $newIssue->labels()->attach($newLabel);
          }
        }

        $newPack->issues()->attach($newIssue);
      }

      $newPack->load('issues.labels');

      return response()->json($newPack);
    }

    public function deletePack($id) {
      $user_id = Auth::id();
      $pack = IssuePack::find($id);

      if($pack->user_id == $user_id) {
        return response()->json($pack->delete());
      } else {
        return response()->json('You are not authorized to delete that pack', 401);
      }
    }

    public function copyPack($id) {
      $user_id = Auth::id();
      $pack = IssuePack::with('issues.labels')->find($id);

      $newPack = new IssuePack();
      $newPack->name = $pack->name;
      $newPack->user_id = $user_id;
      $newPack->save();

      foreach($pack->issues as $issue) {
        $newIssue = new Issue();
        $newIssue->title = $issue->title;
        $newIssue->body = $issue->body;
        $newIssue->save();

        if(isset($issue->labels)) {
          foreach($issue->labels as $label) {
            $newLabel = new Label();
            $newLabel->name = $label->name;
            $newLabel->save();

            $newIssue->labels()->attach($newLabel);
          }
        }

        $newPack->issues()->attach($newIssue);
      }

      $newPack->load('issues.labels');

      return response()->json($newPack);
    }

    public function publishPack($id, Request $request) {
      $user_id = Auth::id();
      $pack = IssuePack::find($id);

      //If the logged in user owns the pack
      if($pack->user_id == $user_id) {
        //Publish / unpublish
        $public = $request->get('public');
        $pack->public = $public;

        return response()->json($pack->save());
      } else { //Otherwise return error
        return response()->json('You are not authorized to publish that pack', 401);
      }
    }

    public function saveSync($id, Request $request) {
      $user_id = Auth::id();

      $input = $request->all();

      $sync = new PackSync();
      $sync->user_id = $user_id;
      $sync->issue_pack_id = $id;
      $sync->application = $input['application'];
      $sync->repo = $input['repo'];
      $sync->url = $input['url'];

      return response()->json($sync->save());
    }

    public function searchPacks () {
      $packs = IssuePack::where('public', '=', true)->get();

      return response()->json($packs);
    }
}
