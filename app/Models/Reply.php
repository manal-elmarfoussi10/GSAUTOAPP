<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Email;
use App\Models\User;

class Reply extends Model
{
    use HasFactory;

   // app/Models/Reply.php
   protected $fillable = [
    'email_id',
    'conversation_id',
    'sender_id',
    'receiver_id',
    'content',
    'file_path',
    'file_name',
  ];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sender()
{
    return $this->belongsTo(User::class, 'sender_id');
}

public function receiver()
{
    return $this->belongsTo(User::class, 'receiver_id');
}
}