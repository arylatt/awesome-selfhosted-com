<?php

return [

    /*
    |--------------------------------------------------------------------------
    | App Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID for GitHub app that provides authentication
    |
    */

    'clientid' => env('GITHUB_CLIENTID'),

    /*
    |--------------------------------------------------------------------------
    | App Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret for GitHub app that provides authentication
    |
    */

    'secret' => env('GITHUB_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | Owner's username + personal access token for scanning the repo via the API.
    | Use the format "Username:PersonalAccessToken"
    |
    */

    'apicred' => env('GITHUB_APICRED'),

    /*
    |--------------------------------------------------------------------------
    | User-Agent
    |--------------------------------------------------------------------------
    |
    | A User-Agent to be provided when accessing the GitHub API, in accordance with their TOS.
    |
    */

    'useragent' => env('GITHUB_USERAGENT'),

];
