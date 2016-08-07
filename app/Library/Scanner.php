<?php

# =========== CONFIG ===========

namespace App\Library;

require_once("grok.php");
$grok = new Grok();

$grokPatterns = array(
	"githubRepos" => "\[%{DATA}]\(https://github\.com/%{USERNAME:username}/%{USERNAME:repo}\)",
	"urls" => "\(%{DATA:url}\)"
);

$today = date("d.m.y");
$timeThreshold = "P6M";
$since = $now->sub(new DateInterval($timeThreshold));
$since = $since->format(DateTime::ATOM);

$fileURL = "https://raw.githubusercontent.com/{$config["gh_repo_owner"]}/{$config["gh_repo_name"]}/master/README.md";

# =========== RETRIEVAL ===========


# =========== PARSING ===========


# =========== SCANNING ===========
