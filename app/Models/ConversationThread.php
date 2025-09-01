<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Email;
use App\Models\Client;

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

    // THIS is the creator relation your controller/view expects:
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function emails()
    {
        return $this->hasMany(Email::class, 'thread_id');
    }

    public function replies()
{
    return $this->hasMany(Reply::class, 'thread_id');
}

 
}