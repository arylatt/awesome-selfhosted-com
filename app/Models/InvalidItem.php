<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvalidItem extends Model
{
    public $primaryKey = 'invalid_item_id';
    protected $fillable = ['invalid_item_text', 'invalid_item_error', 'scan_id'];

    public function Scan()
    {
    	return $this->belongsTo('App\Models\Scan');
    }
}
