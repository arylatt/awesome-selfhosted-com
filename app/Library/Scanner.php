<?php

namespace App\Library;

use Carbon\Carbon;

class Scanner
{
    protected $file;
    protected $grok;
    protected $grokPatterns;
    protected $maxAge;

    protected $headers = [];
    protected $subheaders = [];
    protected $descriptions = [];
    protected $validLinks = [];
    protected $invalidItems = [];

    public function __construct()
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => config('scanner.repositoryurl'),
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $this->file = curl_exec($ch);
        curl_close($ch);

        $this->grok = new Grok();
        $this->grok->addPatterns(config('scanner.customgrok'));
        $this->grokPatterns = [
            'githubRepos' => "\[%{DATA:name}]\(https://github\.com/%{USERNAME:username}/%{USERNAME:repo}\)",
            'urls'        => "\(%{DATA:url}\)",
            'items'       => config('scanner.itemgrokpattern'),
        ];

        $this->maxAge = Carbon::now()->subDays(config('scanner.maintainedthreshold'))->toAtomString();
    }

    public function GetFile()
    {
        return $this->file;
    }

    public function GetGrokPatterns()
    {
        return $this->grokPatterns;
    }

    public function Scan()
    {
        $start = strpos($this->file, config('scanner.starttag')) + strlen(config('scanner.starttag'));
        $end = strpos($this->file, config('scanner.endtag')) - strlen(config('scanner.endtag'));
        if ($start === -1 || $end === -1) {
            return false;
        }
        $end = $end - $start;
        $lines = explode("\n", substr($this->file, $start, $end));
        $lastItem;
        foreach ($lines as $line) {
            if (substr($line, 0, 3) == '## ') {
                array_push($this->headers, substr($line, 3));
                $lastItem = 'h';
            } elseif (substr($line, 0, 4) == '### ') {
                array_push($this->subheaders, substr($line, 4));
                $lastItem = 's';
            } elseif (substr($line, 0, 4) == '  * ') {
                $grokResult = $this->grok->parse($this->grokPatterns['items'], substr($line, 4));
                if ($grokResult === false) {
                    array_push($this->invalidItems, substr($line, 4));
                    $lastItem = 'i';
                } else {
                    array_push($this->validLinks, $grokResult);
                    $lastItem = 'l';
                }
            } else {
                if ($line != '') {
                    if ($lastItem == 'h' || $lastItem == 's') {
                        array_push($this->descriptions, $line);
                    } else {
                        array_push($this->invalidItems, $line);
                    }
                }
            }
        }

        return true;
    }

    public function GetResults()
    {
        return [
            'stats' => [
                'headers'      => count($this->headers),
                'subheaders'   => count($this->subheaders),
                'descriptions' => count($this->descriptions),
                'validLinks'   => count($this->validLinks),
                'invalidItems'  => count($this->invalidItems),
            ],
            'data' => [
                'headers'      => $this->headers,
                'subheaders'   => $this->subheaders,
                'descriptions' => $this->descriptions,
                'validLinks'   => $this->validLinks,
                'invalidItems'  => $this->invalidItems,
            ],
        ];
    }
}
