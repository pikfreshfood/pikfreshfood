<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeerCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'caller_id',
        'callee_id',
        'room_name',
        'call_type',
        'status',
        'offer_sdp',
        'answer_sdp',
        'caller_candidates',
        'callee_candidates',
        'accepted_at',
        'ended_at',
    ];

    protected $casts = [
        'offer_sdp' => 'array',
        'answer_sdp' => 'array',
        'caller_candidates' => 'array',
        'callee_candidates' => 'array',
        'accepted_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function callee()
    {
        return $this->belongsTo(User::class, 'callee_id');
    }
}
