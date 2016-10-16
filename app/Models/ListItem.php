<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    public $primaryKey = 'list_item_id';
    protected $fillable = ['list_item_name', 'list_item_url', 'list_item_description', 'list_item_demo', 'list_item_sourcecode', 'list_item_license', 'list_item_language', 'list_item_proprietary', 'header_id', 'scan_id'];

    public function Scan()
    {
    	return $this->belongsTo('App\Models\Scan');
    }

    public function Header()
    {
    	return $this->belongsTo('App\Models\Header');
    }
}
