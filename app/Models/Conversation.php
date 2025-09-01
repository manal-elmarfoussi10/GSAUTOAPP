<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Email; // Add this for conversations

class Conversation extends Model
{
    protected $table = 'emails'; // Point to existing table
    
    protected $fillable = [
        // Include all fields from your screenshot
        'sender', 'receiver', 'subject', 'content', 
        'label', 'label_color', 'important', 'is_deleted', 'folder',
        'client_id' // Add this if missing
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'email_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}