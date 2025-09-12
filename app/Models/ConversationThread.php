<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversationThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'company_id',
        'subject',
        'creator_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function emails()
    {
        // ✅ email FK is thread_id
        return $this->hasMany(Email::class, 'thread_id');
    }

    public function replies()
    {
        // ✅ reply FK is thread_id
        return $this->hasMany(Reply::class, 'thread_id');
    }
}