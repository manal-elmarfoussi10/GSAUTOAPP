<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Conversation;
use App\Models\Reply;
use App\Models\User;
use App\Models\Client;
use App\Models\Company;

class Email extends Model
{
    use HasFactory;

 protected $fillable = [
    'conversation_id',
    'sender_id',
    'receiver_id',
    'subject',
    'content',
    'label',
    'label_color',
    'important',
    'is_deleted',
    'folder',
    'client_id',
    'company_id',
    'file_path',      // ← ajoute ceci
    'file_name',      // ← et ceci
];

    public function conversation()
    {
        return $this->belongsTo(ConversationThread::class, 'conversation_id');
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function markAsRead()
{
    $this->update(['read' => true]);
}

public function conversationThread()
{
    return $this->belongsTo(ConversationThread::class, 'conversation_id');
}
}