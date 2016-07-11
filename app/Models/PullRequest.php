<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PullRequest extends Model
{
    public $primaryKey = 'pr_id';
    protected $fillable = ['pr_id', 'pr_author', 'pr_status', 'pr_title', 'pr_url', 'pr_mergeable', 'pr_locked'];
}
