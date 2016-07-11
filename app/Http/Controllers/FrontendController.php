<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Models\User;

class FrontendController extends Controller
{
	public function Index(Request $req) {
		return view('frontend.index', ['user' => ((Auth::check()) ? Auth::user()->user_name : 'Anon')]);
	}

    public function Login(Request $req) {
    	if(!Auth::check()) {
	    	if(!$req->session()->has('github.state')) {
		    	$state = str_random(128);
		    	$req->session()->put('github.state', $state);
		    	return redirect('https://github.com/login/oauth/authorize?client_id=' . config('github.clientid') . '&state=' . $state);
		    } else {
		    	$state = $req->session()->pull('github.state');
		    	if($req->state === $state) {
			    	$check_auth = curl_init();
			    	curl_setopt_array($check_auth, [
			    		CURLOPT_URL => 'https://github.com/login/oauth/access_token',
			    		CURLOPT_RETURNTRANSFER => true,
			    		CURLOPT_POSTFIELDS => [
			    			'client_id' => config('github.clientid'),
			    			'client_secret' => config('github.secret'),
			    			'code' => $req->code,
			    			'state' => $state,
			    		],
			    		CURLOPT_HTTPHEADER => ['Accept: application/json'],
			    	]);
			    	$auth = json_decode(curl_exec($check_auth));
			    	if(isset($auth->access_token)) {
			    		$req->session()->put('github.access_token', $auth->access_token);
			    		User::AuthByAccessToken($auth->access_token);
			    		return redirect('/');
			    	}
			    	return "Login Error ¯\_(ツ)_/¯";
			    } else {
			    	return "Invalid State ¯\_(ツ)_/¯";
			    }
		    }
		} else {
			return redirect('/');
		}
    }

    public function Logout(Request $req) {
    	Auth::logout();
    	return redirect('/');
    }

    public function DisplayMarkdown(Request $req) {
    	$md = new \cebe\markdown\GithubMarkdown();
    	$md = $md->parse(file_get_contents("https://raw.githubusercontent.com/kickball/awesome-selfhosted/master/README.md"));
    	return view('frontend.markdown', ['title' => 'Markdown - Awesome-Selfhosted', 'md' => $md]);
    }
}
