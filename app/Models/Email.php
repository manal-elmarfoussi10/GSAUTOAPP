<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',      // ✅ use thread_id everywhere (not conversation_id)
        'sender_id',
        'receiver_id',    // can be NULL to broadcast to service
        'subject',
        'content',
        'label',
        'label_color',
        'tag',            // if you use "important" via tag
        'tag_color',
        'important',      // keep only if you truly have this column
        'is_deleted',
        'is_read',        // if present
        'folder',
        'client_id',
        'company_id',
        'file_path',
        'file_name',
    ];

    /* Relationships */
    public function thread()
    {
        return $this->belongsTo(ConversationThread::class, 'thread_id');
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
        // ascending so timeline reads top→bottom
        return $this->hasMany(Reply::class)->orderBy('created_at');
    }

    /* Scopes / helpers */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function markAsRead(): void
    {
        if ($this->isFillable('is_read')) {
            $this->update(['is_read' => true]);
        }
    }
}