<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Log;

use App\Http\Requests;
use App\Issue;
use App\IssuePack;
use App\Label;

class PackController extends Controller
{
    public function getMyPacks() {
      $user_id = Auth::id();

      return response()->json(IssuePack::where('user_id', '=', $user_id)->with('issues.labels')->get());
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
      return 'Deleting ' . $id;
    }
}
