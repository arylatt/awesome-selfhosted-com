<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    |
    | URL for repository to scan.
    |
    */

    'repositoryurl' => env('GITHUB_REPOSITORY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Grok Pattern
    |--------------------------------------------------------------------------
    |
    | Grok pattern used for repository items.
    |
    */

    'grokpattern' => env('GROK_PATTERN'),

    /*
    |--------------------------------------------------------------------------
    | Maintained Threshold
    |--------------------------------------------------------------------------
    |
    | The integer threshold in days for projects classified as maintained.
    |
    */

    'maintainedthreshold' => env('MAINTAINED_THRESHOLD'),

];
