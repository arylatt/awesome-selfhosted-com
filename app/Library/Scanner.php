<?php

namespace App\Library;

use App\Models\Header;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
        $lastHeaderId = 0;
        $lastHeaderLevel = 2;
        $headers = new Collection();
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '##') {
                $headerText = substr($line, strpos($line, '# ') + 2);
                $headerLevel = strpos($line, '# ') + 1;
                if ($headerLevel == 2) {
                    $headerParent = 0;
                } elseif ($headerLevel == $lastHeaderLevel) {
                    $headerParent == Header::find($lastHeaderId)->header_parent;
                } elseif ($headerLevel > $lastHeaderLevel) {
                    $headerParent = $lastHeaderId;
                } else {
                    dd($headers->last(function ($k, $v) {
                        return $v['header_level'] == $headerLevel;
                    }));
                    $headerParent = $headers->where('header_level', '=', $headerLevel)->last()->header_parent;
                }
                $header = Header::where('header_text', '=', $headerText)->first();
                if (!$header) {
                    $header = Header::create(['header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
                    $lastHeaderId = $header->header_id;
                    $lastHeaderLevel = $header->header_level;
                    $headers->push(['header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
                } else {
                    if ($header->header_level != $headerLevel || $header->header_parent != $headerParent) {
                        $header->header_level = $headerLevel;
                        $header->header_parent = $headerParent;
                        $header->save();
                        $lastHeaderId = $header->header_id;
                        $lastHeaderLevel = $header->header_level;
                        $headers->push(['header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
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
                'headers'       => count($this->headers),
                'subheaders'    => count($this->subheaders),
                'descriptions'  => count($this->descriptions),
                'validLinks'    => count($this->validLinks),
                'invalidItems'  => count($this->invalidItems),
            ],
            'data' => [
                'headers'       => $this->headers,
                'subheaders'    => $this->subheaders,
                'descriptions'  => $this->descriptions,
                'validLinks'    => $this->validLinks,
                'invalidItems'  => $this->invalidItems,
            ],
        ];
    }
}
