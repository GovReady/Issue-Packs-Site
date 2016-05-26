<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\IssuePack;

class PackController extends Controller
{
    public function getMyPacks() {
      return IssuePack::all();
    }
}
