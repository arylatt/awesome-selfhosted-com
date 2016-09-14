<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    public $privateKey = 'description_id';
    protected $fillable = ['description_text', 'header_id', 'scan_id'];

    public function Header()
    {
        return $this->belongsTo('App\Models\Header');
    }

    public function Scan()
    {
        return $this->belongsTo('App\Models\Scan');
    }
}
