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
    protected $headers;
    protected $lastHeaderId;
    protected $lastHeaderLevel;
    protected $lastLineHeader = false;
    protected $descriptions;
    protected $validLinks;
    protected $invalidItems;

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
        $this->lastHeaderId = 0;
        $this->lastHeaderLevel = 2;
        $this->headers = new Collection();
        $this->validLinks = new Collection();
        $this->invalidItems = new Collection();
        $this->descriptions = new Collection();
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '##') {
                $this->ParseHeader($line);
            } else if(preg_match('/ *\*[^\*] *\[/', $line)) {
                $this->ParseItem($line);
            } elseif (strlen($line) && $this->lastLineHeader) {
                $this->ParseDescription($line);
            }
        }
        $this->Cleanup();
    }

    public function GetResults()
    {
        return [
            'stats' => [
                'headers'       => count($this->headers),
                'descriptions'  => count($this->descriptions),
                'validLinks'    => count($this->validLinks),
                'invalidItems'  => count($this->invalidItems),
            ],
            'data' => [
                'headers'       => $this->headers,
                'descriptions'  => $this->descriptions,
                'validLinks'    => $this->validLinks,
                'invalidItems'  => $this->invalidItems,
            ],
        ];
    }

    protected function ParseHeader($line)
    {
        $headerText = substr($line, strpos($line, '# ') + 2);
        $headerLevel = strpos($line, '# ') + 1;
        if ($headerLevel == 2) {
            $headerParent = 0;
        } elseif ($headerLevel == $this->lastHeaderLevel) {
            $headerParent = Header::find($this->lastHeaderId)->header_parent;
        } elseif ($headerLevel > $this->lastHeaderLevel) {
            $headerParent = $this->lastHeaderId;
        } else {
            $headerParent = $this->headers->where('header_level', $headerLevel)->last()['header_parent'];
        }
        $header = Header::where('header_text', '=', $headerText)->first();
        if (!$header) {
            $header = Header::create(['header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
            $this->lastHeaderId = $header->header_id;
            $this->lastHeaderLevel = $header->header_level;
            $this->headers->push(['header_id' => $header->header_id, 'header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
        } else {
            if ($header->header_level != $headerLevel || $header->header_parent != $headerParent) {
                $header->header_level = $headerLevel;
                $header->header_parent = $headerParent;
                $header->save();
            }
            $this->lastHeaderId = $header->header_id;
            $this->lastHeaderLevel = $header->header_level;
            $this->headers->push(['header_id' => $header->header_id, 'header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
        }
        $this->lastLineHeader = true;
    }

    protected function ParseItem($line)
    {
        $this->lastLineHeader = false;
        $item = $this->grok->parse($this->grokPatterns['items'], $line);
        if ($item) {
            $sc = '';
            $d = '';
            if ($item['l2_t'] != '') {
                if ($item['l1_t'] != 'Demo' || $item['l2_t'] != 'Source Code') {
                    $this->invalidItems->push($line);

                    return;
                }
                $sc = $item['l2_u'];
            }
            if ($item['l1_t'] == 'Source Code') {
                $sc = $item['l1_u'];
            } elseif ($item['l1_t'] == 'Demo') {
                $d = $item['l1_u'];
            }
            $this->validLinks->push(['name' => $item['name'], 'url' => $item['url'], 'desc' => $item['desc'], 'source' => $sc, 'demo' => $d, 'lic' => $item['license'], 'lang' => $item['language'], 'prop' => (strlen($item['prop']) ? true : false)]);

            return;
        }
        $this->invalidItems->push($line);
    }

    protected function ParseDescription($line)
    {
        $this->lastLineHeader = false;
        $this->descriptions->push(['desc' => $line, 'header_id' => $this->lastHeaderId]);
    }

    protected function Cleanup()
    {
        foreach (Header::all() as $header) {
            if (!count($this->headers->where('header_id', $header->header_id)->first())) {
                $header->delete();
            }
        }
    }
}