<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;
use App\IssuePack;

class PackController extends Controller
{
    public function getMyPacks() {
      $user_id = Auth::id();

      return response()->json(IssuePack::where('user_id', '=', $user_id)->with('issues.labels')->get());
    }

    public function deletePack($id) {
      return 'Deleting ' . $id;
    }
}
