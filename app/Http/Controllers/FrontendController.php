<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Library\Scanner;

class FrontendController extends Controller
{
    public function Index(Request $req)
    {
        return view('frontend.index');
    }

    public function View(Request $req)
    {
        return view('frontend.view', ['title' => 'View']);
    }

    public function DisplayMarkdown(Request $req)
    {
        $md = new \cebe\markdown\GithubMarkdown();
        $md = $md->parse(file_get_contents('https://raw.githubusercontent.com/kickball/awesome-selfhosted/master/README.md'));

        return view('frontend.markdown', ['title' => 'Markdown', 'md' => $md]);
    }

    public function Submit(Request $req)
    {
        if (!Auth::check()) {
            return view('frontend.nologin', ['title' => 'Hold on a sec...']);
        }

        return view('frontend.submit', ['title' => 'Submit Item']);
    }

    public function Team(Request $req)
    {
        $collaborators = User::where('user_collab', '=', '1')->orWhere('user_admin', '=', '1')->get();

        return view('frontend.team', ['title' => 'Team', 'collabs' => $collaborators]);
    }

    public function Login(Request $req)
    {
        if (isset($req->backTo)) {
            $req->session()->put('login.redirect', $req->backTo);
        }
        if (!Auth::check()) {
            if (!$req->session()->has('github.state')) {
                $state = str_random(128);
                $req->session()->put('github.state', $state);

                return redirect('https://github.com/login/oauth/authorize?client_id='.config('github.clientid').'&state='.$state);
            } else {
                $state = $req->session()->pull('github.state');
                if ($req->state === $state) {
                    $check_auth = curl_init();
                    curl_setopt_array($check_auth, [
                        CURLOPT_URL            => 'https://github.com/login/oauth/access_token',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS     => [
                            'client_id'     => config('github.clientid'),
                            'client_secret' => config('github.secret'),
                            'code'          => $req->code,
                            'state'         => $state,
                        ],
                        CURLOPT_HTTPHEADER => ['Accept: application/json'],
                    ]);
                    $auth = json_decode(curl_exec($check_auth));
                    if (isset($auth->access_token)) {
                        $req->session()->put('github.access_token', $auth->access_token);
                        User::AuthByAccessToken($auth->access_token);

                        return redirect($req->session()->pull('login.redirect', '/'));
                    }

                    return "Login Error ¯\_(ツ)_/¯";
                } else {
                    return "Invalid State ¯\_(ツ)_/¯";
                }
            }
        } else {
            return redirect($req->session()->pull('login.redirect', '/'));
        }
    }

    public function Logout(Request $req)
    {
        Auth::logout();

        return redirect('/');
    }

    public function ScanTest(Request $req) //Debugging code. Remove me
    {
        $scanner = new Scanner();
        $scanner->Scan();
        dd($scanner->GetResults());
    }
}
