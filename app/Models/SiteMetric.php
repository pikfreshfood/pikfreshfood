<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMetric extends Model
{
    protected $fillable = [
        'metric_key',
        'metric_value',
    ];

    public $timestamps = true;
}

