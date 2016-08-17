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

    'grokpattern' => env('GITHUB_ITEM_GROK_PATTERN'),

    /*
    |--------------------------------------------------------------------------
    | Maintained Threshold
    |--------------------------------------------------------------------------
    |
    | The integer threshold in days for projects classified as maintained.
    |
    */

    'maintainedthreshold' => env('GITHUB_REPOSITORY_MAINTAINED_THRESHOLD'),

];
