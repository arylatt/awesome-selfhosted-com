<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;

class CollabController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');

    	if(!Auth::user()->IsCollab()) {
    		abort(403);
    	}
    }

    public function Index(Request $req) {
    	return "Collab";
    }
}
