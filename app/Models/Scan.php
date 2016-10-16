<?php

namespace App\Models;

use App\Library\Scanner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    public $primaryKey = 'scan_id';
    protected $fillable = ['scan_id', 'scan_start', 'scan_end', 'scan_creator', 'scan_status'];

    const FAILED = 0;
    const PENDING = 1;
    const SUCCEEDED = 2;

    public function Creator()
    {
        return $this->belongsTo('App\Models\User', 'scan_creator', 'user_id');
    }

    public function Headers()
    {
    	return $this->hasMany('App\Models\Header');
    }

    public function Descriptions()
    {
    	return $this->hasMany('App\Models\Description');
    }

    public function ListItems()
    {
    	return $this->hasMany('App\Models\ListItem');
    }

    public function InvalidItems()
    {
    	return $this->hasMany('App\Models\InvalidItem');
    }

    public function Log()
    {
        return $this->hasMany('App\Models\ScanLog');
    }

    public static function NewScan($auto = false)
    {
    	if($auto) {
    		$creator = -1;
    	} else {
    		$creator = auth()->user()->user_id;
    	}
    	$s = self::create([
    		'scan_start' => Carbon::Now()->toDateTimeString(),
    		'scan_creator' => $creator,
    		'scan_status' => self::PENDING,
    	]);
    	$scanner = new Scanner($s);
    	$scanner->Scan();
        dd($scanner->GetResults());
    }

    public function FormatStatus()
    {
        switch ($this->scan_status) {
            case 0:
                return '<td class="negative">Failed</td>';
                break;
            case 1:
                return '<td class="warning">Pending</td>';
                break;
            case 2:
                return '<td class="positive">Succeeded</td>';
                break;
        }
    }
}
