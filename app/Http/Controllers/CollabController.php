<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class CollabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        if (!Auth::user()->IsCollab()) {
            abort(403);
        }
    }

    public function Index(Request $req)
    {
        return 'Collab';
    }
}
