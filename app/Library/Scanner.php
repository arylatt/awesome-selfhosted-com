<?php

namespace App\Library;

use App\Models\Description;
use App\Models\Header;
use App\Models\InvalidItem;
use App\Models\ListItem;
use App\Models\Scan;
use App\Models\ScanLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Scanner
{
    protected $scan;
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

    public function __construct($scan)
    {
        $this->scan = $scan;
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
            } elseif (preg_match('/ *\*[^\*] *\[/', $line)) {
                $this->ParseItem($line);
            } elseif (strlen($line) && $this->lastLineHeader && $line != '**[`^        back to top        ^`](#)**') {
                $this->ParseDescription($line);
            }
        }
        $this->Cleanup();
        $this->scan->scan_end = Carbon::Now()->toDateTimeString();
        $this->scan->scan_status = Scan::SUCCEEDED;
        $this->scan->save();
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
            $header = Header::create(['header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent, 'scan_id' => $this->scan->scan_id]);
            $this->Log('Header created (#'.$header->header_id.')');
            $this->lastHeaderId = $header->header_id;
            $this->lastHeaderLevel = $header->header_level;
            $this->headers->push(['header_id' => $header->header_id, 'header_text' => $headerText, 'header_level' => $headerLevel, 'header_parent' => $headerParent]);
        } else {
            if ($header->header_level != $headerLevel || $header->header_parent != $headerParent) {
                $header->header_level = $headerLevel;
                $header->header_parent = $headerParent;
                $header->scan_id = $this->scan->scan_id;
                $this->Log('Header updated (#'.$header->header_id.')');
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
                    $i = InvalidItem::create(['invalid_item_text' => $line, 'invalid_item_error' => 'Syntax error', 'scan_id' => $this->scan->scan_id]);
                    $this->invalidItems->push(['invalid_id' => $i->invalid_item_id, 'line' => $line]);
                    $this->Log('Invalid item detected (#'.$i->invalid_item_id.')');

                    return;
                }
                $sc = $item['l2_u'];
            }
            if ($item['l1_t'] == 'Source Code') {
                $sc = $item['l1_u'];
            } elseif ($item['l1_t'] == 'Demo') {
                $d = $item['l1_u'];
            }
            $invalid = [];
            if (isset($item['url']) && !empty($item['url'])) {
                if (($err = $this->URLCheck($item['url'])) !== true) {
                    array_push($invalid, $err);
                }
            }
            if (isset($d) && !empty($d)) {
                if (($err = $this->URLCheck($d)) !== true) {
                    array_push($invalid, $err);
                }
            }
            if (isset($sc) && !empty($sc)) {
                if (($err = $this->URLCheck($sc)) !== true) {
                    array_push($invalid, $err);
                }
            }
            $listItem = ListItem::where('list_item_name', '=', $item['name'])->first();
            if (!$listItem) {
                if (count($invalid)) {
                    $i = InvalidItem::create(['invalid_item_text' => $line, 'invalid_item_error' => implode(', ', $invalid), 'scan_id' => $this->scan->scan_id]);
                    $this->Log('Invalid item detected (#'.$i->invalid_item_id.')');
                    $this->invalidItems->push(['invalid_id' => $i->invalid_item_id, 'line' => $line, 'error' => implode(', ', $invalid)]);

                    return;
                }
                $listItem = ListItem::create(['list_item_name' => $item['name'], 'list_item_url' => $item['url'], 'list_item_description' => $item['desc'], 'list_item_sourcecode' => $sc, 'list_item_demo' => $d, 'list_item_license' => $item['license'], 'list_item_language' => $item['language'], 'list_item_proprietary' => (strlen($item['prop']) ? true : false), 'header_id' => $this->lastHeaderId, 'scan_id' => $this->scan->scan_id]);
                $this->Log('ListItem created (#'.$listItem->list_item_id.')');
            } else {
                if (count($invalid)) {
                    $listItem->delete();
                    $i = InvalidItem::create(['invalid_item_text' => $line, 'invalid_item_error' => implode(', ', $invalid), 'scan_id' => $this->scan->scan_id]);
                    $this->Log('Invalid item detected (#'.$i->invalid_item_id.') - Removed from list.');
                    $this->invalidItems->push(['invalid_id' => $i->invalid_item_id, 'line' => $line, 'error' => implode(', ', $invalid)]);

                    return;
                }
                if ($listItem->list_item_url != $item['url']) {
                    $listItem->list_item_url = $item['url'];
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_description != $item['desc']) {
                    $listItem->list_item_description = $item['desc'];
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_sourcecode != $sc) {
                    $listItem->list_item_sourcecode = $sc;
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_demo != $d) {
                    $listItem->list_item_demo = $d;
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_license != $item['license']) {
                    $listItem->list_item_license = $item['license'];
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_language != $item['language']) {
                    $listItem->list_item_language = $item['language'];
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->list_item_proprietary != (strlen($item['prop']) ? true : false)) {
                    $listItem->list_item_proprietary = (strlen($item['prop']) ? true : false);
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->header_id != $this->lastHeaderId) {
                    $listItem->header_id = $this->lastHeaderId;
                    $listItem->scan_id = $this->scan->scan_id;
                }
                if ($listItem->scan_id != $this->scan->scan_id) {
                    $listItem->save();
                    $this->Log('ListItem updated (#'.$listItem->list_item_id.')');
                }
            }
            $this->validLinks->push(['item_id' => $listItem->list_item_id, 'name' => $item['name'], 'url' => $item['url'], 'desc' => $item['desc'], 'source' => $sc, 'demo' => $d, 'lic' => $item['license'], 'lang' => $item['language'], 'prop' => (strlen($item['prop']) ? true : false)]);

            return;
        }
        $i = InvalidItem::create(['invalid_item_text' => $line, 'invalid_item_error' => 'Syntax error', 'scan_id' => $this->scan->scan_id]);
        $this->invalidItems->push(['invalid_id' => $i->invalid_item_id, 'line' => $line]);
        $this->Log('Invalid item detected (#'.$i->invalid_item_id.')');
    }

    protected function ParseDescription($line)
    {
        $this->lastLineHeader = false;
        $desc = Description::where('description_text', '=', $line)->first();
        if (!$desc) {
            $desc = Description::create(['description_text' => $line, 'header_id' => $this->lastHeaderId, 'scan_id' => $this->scan->scan_id]);
            $this->Log('Description added (#'.$desc->description_id.')');
        } elseif ($desc->header_id != $this->lastHeaderId) {
            $desc->header_id = $this->lastHeaderId;
            $desc->scan_id = $this->scan->scan_id;
            $desc->save();
            $this->Log('Description updated (#'.$desc->description_id.')');
        }
        $this->descriptions->push(['desc_id' => $desc->description_id, 'desc' => $line, 'header_id' => $this->lastHeaderId]);
    }

    protected function Cleanup()
    {
        foreach (Header::all() as $header) {
            if (!count($this->headers->where('header_id', $header->header_id)->first())) {
                $header->delete();
            }
        }
        foreach (Description::all() as $description) {
            if (!count($this->descriptions->where('desc_id', $description->description_id)->first())) {
                $description->delete();
            }
        }
        foreach (ListItem::all() as $listItem) {
            if (!count($this->validLinks->where('item_id', $listItem->list_item_id)->first())) {
                $listItem->delete();
            }
        }
    }

    protected function URLCheck($url)
    {
        if (preg_match('/http(s)?\:\/\/(www\.)?github\.com\/(?P<user>[A-Za-z0-9-_.]*)\/(?P<repo>[A-Za-z0-9-_.]*)/', $url, $matches)) {
            if (empty($matches['user']) || empty($matches['repo'])) {
                return 'Not a repo';
            }
            $api = curl_init();
            curl_setopt_array($api, [
                CURLOPT_URL            => 'https://api.github.com/repos/'.$matches['user'].'/'.$matches['repo'].'?client_id='.env('GITHUB_CLIENTID').'&client_secret='.env('GITHUB_SECRET'),
                CURLOPT_HTTPHEADER     => ['User-Agent: '.env('GITHUB_USERAGENT')],
                CURLOPT_RETURNTRANSFER => true,
            ]);
            try {
                $branch = json_decode(curl_exec($api));
                $branch = (isset($branch->default_branch)) ? $branch->default_branch : 'master';
                $api = curl_init();
                curl_setopt_array($api, [
                    CURLOPT_URL            => 'https://api.github.com/repos/'.$matches['user'].'/'.$matches['repo'].'/branches/'.$branch.'?client_id='.env('GITHUB_CLIENTID').'&client_secret='.env('GITHUB_SECRET'),
                    CURLOPT_HTTPHEADER     => ['User-Agent: '.env('GITHUB_USERAGENT')],
                    CURLOPT_RETURNTRANSFER => true,
                ]);
                if (Carbon::parse(json_decode(curl_exec($api))->commit->commit->author->date)->diffInDays() > config('scanner.maintainedthreshold')) {
                    return 'Too old';
                }
            } catch (\ErrorException $e) {
                return 'Bad repo';
            }
        } else {
            $check = curl_init();
            curl_setopt_array($check, [
                CURLOPT_URL            => $url,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
            ]);
            curl_exec($check);
            $header = curl_getinfo($check);
            if ($header['http_code'] == '0') {
                return $url.' - cURL error: '.curl_error($check);
            } elseif ($header['http_code'] != '200') {
                return $url.' - Bad response code: '.$header['http_code'];
            }
        }

        return true;
    }

    protected function Log($text)
    {
        ScanLog::create(['scan_id' => $this->scan->scan_id, 'scan_log_text' => $text]);
    }
}
