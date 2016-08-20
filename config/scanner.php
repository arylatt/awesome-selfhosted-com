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

    'repositoryurl' => 'https://raw.githubusercontent.com/kickball/awesome-selfhosted/master/README.md',

    /*
    |--------------------------------------------------------------------------
    | Custom Grok Patterns
    |--------------------------------------------------------------------------
    |
    | Custom Grok patterns used to parse the data
    |
    */

    'customgrok' => [
        'PROPRIETARY' => '(`⚠` )?',
        'DESCRIPTION' => '.{1,249}\.',
        'LINKS' => '(\(\[%{LINK_TITLE:l1_t}\]\(%{URL:l1_u}\)(, \[%{LINK_TITLE:l2_t}\]\(%{URL:l2_u}\))?(, \[%{LINK_TITLE:l3_t}\]\(%{URL:l3_u}\))?\) )?',
        'LINK_TITLE' => '(Demo|Example|Source Code)',
        'URL' => '%{URIPROTO}://(?:%{USER}(?::[^@]*)?@)?(?:%{IPORHOST})?(?:%{URIPATHPARAM})?',
    ],


    /*
    |--------------------------------------------------------------------------
    | Item Grok Pattern
    |--------------------------------------------------------------------------
    |
    | Grok pattern used for repository items.
    |
    */

    'itemgrokpattern' => '\[%{DATA:name}\]\(%{URI:url}\) %{PROPRIETARY:prop}- %{DESCRIPTION:desc} %{LINKS:links}`%{DATA:license}` `%{DATA:language}`',

    /*
    |--------------------------------------------------------------------------
    | Maintained Threshold
    |--------------------------------------------------------------------------
    |
    | The integer threshold in days for projects classified as maintained.
    |
    */

    'maintainedthreshold' => 90,

    /*
    |--------------------------------------------------------------------------
    | Start scan tag
    |--------------------------------------------------------------------------
    |
    | The marker in the file to indicate the start of the list
    |
    */

    'starttag' => '<!-- BEGIN SOFTWARE LIST -->',

    /*
    |--------------------------------------------------------------------------
    | End scan tag
    |--------------------------------------------------------------------------
    |
    | The marker in the file to indicate the end of the list
    |
    */

    'endtag' => '<!-- END SOFTWARE LIST -->',

];
