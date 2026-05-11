<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'sender_type',
        'user_id',
        'admin_id',
        'sender_name',
        'message',
        'is_read_by_admin',
        'is_read_by_client',
    ];

    protected $casts = [
        'is_read_by_admin' => 'boolean',
        'is_read_by_client' => 'boolean',
    ];

    public function thread()
    {
        return $this->belongsTo(SupportChatThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
