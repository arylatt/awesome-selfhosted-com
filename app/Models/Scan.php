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

    public static function New($auto = false)
    {
        if ($auto) {
            $creator = -1;
        } else {
            $creator = auth()->user()->user_id;
        }
        $s = self::create([
            'scan_start'   => Carbon::Now(),
            'scan_creator' => $creator,
            'scan_status'  => self::PENDING,
        ]);
        $scanner = new Scanner($s);
        $scanner->Scan();
    }
}
