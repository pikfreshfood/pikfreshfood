<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorLiveVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'title',
        'video_path',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

