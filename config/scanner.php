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

    'repositoryurl' => env('REPOSITORY_URL'),

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
    | The threshold for projects classified as maintained.
    |
    */

    'maintainedthreshold' => env('MAINTAINED_THRESHOLD'),

];
