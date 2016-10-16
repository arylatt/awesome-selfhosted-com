<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    public $primaryKey = 'header_id';
    protected $fillable = ['header_text', 'header_level', 'header_parent', 'scan_id'];

    public function SubHeaders()
    {
        return $this->hasMany('App\Models\Header', 'header_parent');
    }

    public function ParentHeader()
    {
        return $this->belongsTo('App\Models\Header', 'header_id', 'header_parent');
    }

    public function Description()
    {
        return $this->hasOne('App\Models\Description');
    }

    public function ListItems()
    {
        return $this->hasMany('App\Models\ListItem');
    }

    public function Scan()
    {
    	return $this->belongsTo('App\Models\Scan');
    }

    public static function MainHeaders()
    {
        return self::where('header_parent', '=', 0)->orderBy('header_text', 'ASC')->get();
    }
}
