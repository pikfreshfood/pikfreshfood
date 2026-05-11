<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'buyer_id',
        'room_name',
        'call_type',
        'status',
        'offer_sdp',
        'answer_sdp',
        'buyer_candidates',
        'vendor_candidates',
        'accepted_at',
        'ended_at',
    ];

    protected $casts = [
        'offer_sdp' => 'array',
        'answer_sdp' => 'array',
        'buyer_candidates' => 'array',
        'vendor_candidates' => 'array',
        'accepted_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
