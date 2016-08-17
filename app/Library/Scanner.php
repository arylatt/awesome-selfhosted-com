<?php

namespace App\Library;

use Carbon;

class Scanner
{
    public function Scan()
    {
        $grok = new Grok();

        $grokPatterns = [
            'githubRepos' => "\[%{DATA}]\(https://github\.com/%{USERNAME:username}/%{USERNAME:repo}\)",
            'urls'        => "\(%{DATA:url}\)",
            'items'       => config('itemgrokpattern'),
        ];

        $today = Carbon::now();
        $since = $now->subDays(config('maintainedthreshold'));
        $since = $since->toAtomString();

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL           => config('repositoryurl'),
            CURLOPT_RETURNTRANSFER=> true,
        ]);
        $filecontents = curl_exec($ch);
        curl_close($ch);

        
    }
}
