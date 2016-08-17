<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Repository URL
    |--------------------------------------------------------------------------
    |
    | URL for repository to scan.
    |
    */

    'repositoryurl' => env('GITHUB_REPOSITORY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Item Grok Pattern
    |--------------------------------------------------------------------------
    |
    | Grok pattern used for repository items.
    |
    */

    'itemgrokpattern' => env('GITHUB_ITEM_GROK_PATTERN'),

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
