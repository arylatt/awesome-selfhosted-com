<?php

namespace App\Models;

use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'user', 'user_name', 'user_email', 'user_bio', 'github_url', 'github_accesstoken', 'user_collab', 'user_admin'];
    protected $hidden = ['remember_token'];

    public static function AuthByAccessToken($access_token)
    {
        $get_user = curl_init();
        curl_setopt_array($get_user, [
            CURLOPT_URL            => 'https://api.github.com/user',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: token '.$access_token, 'Accept: application/json'],
            CURLOPT_USERAGENT      => config('github.useragent'),
        ]);
        $userInfo = json_decode(curl_exec($get_user));
        if (($u = self::find($userInfo->id))) {
            $u->user = $userInfo->login;
            $u->user_name = (isset($userInfo->name) ? $userInfo->name : $userInfo->login);
            $u->user_email = (isset($userInfo->email) ? $userInfo->email : $u->user.'@users.noreply.github.com');
            $u->github_url = $userInfo->html_url;
            $u->github_accesstoken = $access_token;
            $u->save();
            Auth::login($u, true);
        } else {
            $u = self::create([
                'user_id'            => $userInfo->id,
                'user'               => $userInfo->login,
                'user_name'          => (isset($userInfo->name) ? $userInfo->name : $userInfo->login),
                'user_email'         => (isset($userInfo->email) ? $userInfo->email : $u->user.'@users.noreply.github.com'),
                'user_bio'           => (isset($userInfo->bio) ? $userInfo->bio : 'This user has not set a description yet...'),
                'github_url'         => $userInfo->html_url,
                'github_accesstoken' => $access_token,
            ]);
            Auth::login($u, true);
        }
    }

    public function Avatar()
    {
        return 'https://avatars.githubusercontent.com/u/'.$this->user_id;
    }

    public function IsAdmin()
    {
        return $this->user_admin;
    }

    public function IsCollab()
    {
        if ($this->IsAdmin()) {
            return true;
        }

        return $this->user_collab;
    }

    public function StaffBio()
    {
        return $this->user_bio;
    }
}
