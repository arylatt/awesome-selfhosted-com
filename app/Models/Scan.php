<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    public $primaryKey = "scan_id";
    protected $fillable = ["scan_id", "scan_start", "scan_end", "scan_creator", "scan_type", "scan_status"];
}
