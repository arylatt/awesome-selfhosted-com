<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Auth;
use Carbon\Carbon;
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
        $scans = Scan::take(10)->orderBy('created_at', 'desc')->get();
        $last = Scan::all()->last();
        if (!$last) {
            $last = ['color' => 'red', 'text' => 'N/A'];
        } else {
            switch ($last->scan_status) {
                case 1:
                    $last = ['color' => 'orange', 'text' => Carbon::parse($last->scan_start)->format('d/m/Y H:i')];
                    break;
                case 2:
                    $last = ['color' => 'green', 'text' => Carbon::parse($last->scan_start)->format('d/m/Y H:i')];
                    break;
                default:
                    $last = ['color' => 'red', 'text' => Carbon::parse($last->scan_start)->format('d/m/Y H:i')];
                    break;
            }
        }

        return view('mgmt.index', ['title' => 'Dashboard', 'scans' => $scans, 'last' => $last]);
    }

    public function ViewResults(Request $req, $id)
    {
        $scan = Scan::findOrFail($id);

        return view('mgmt.results', ['title' => 'Scan Results', 'scan' => $scan]);
    }
}
