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
		];

		$today = Carbon::now();
		$since = $now->subDays(config('maintainedthreshold'));
		$since = $since->toAtomString();

		$fileURL = config('repositoryurl');
	}
}