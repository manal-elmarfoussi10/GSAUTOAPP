<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_id',
        'thread_id',     // ✅ keep the thread reference for faster loads
        'sender_id',
        'receiver_id',
        'content',
        'file_path',
        'file_name',
        'is_read',       // use one name if you track read state
    ];

    /* Relationships */
    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function thread()
    {
        return $this->belongsTo(ConversationThread::class, 'thread_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // convenience alias used by some blades
    public function senderUser()
    {
        return $this->sender();
    }

    public function receiverUser()
    {
        return $this->receiver();
    }
}