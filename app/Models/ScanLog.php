<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    public $primaryKey = 'scan_log_id';
    protected $fillable = ['scan_id', 'scan_log_text'];

    public function Scan()
    {
        return $this->belongsTo('App\Models\Scan', 'scan_id', 'scan_id');
    }
}
