<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminBarcode extends Model
{
    protected $fillable = [
        'title',
        'barcode_value',
        'scan_text',
        'background_information',
        'barcode_path',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
