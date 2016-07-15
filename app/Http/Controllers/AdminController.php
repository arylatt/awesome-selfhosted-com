<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        if (!Auth::user()->IsAdmin()) {
            abort(403);
        }
    }

    public function Index(Request $req)
    {
        return 'Admin';
    }
}
