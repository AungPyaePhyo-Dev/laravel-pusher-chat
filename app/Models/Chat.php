<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";
    protected $guarded = ['id'];

    public function participants() 
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    public function messages() 
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function scopeHasParticipant($query, int $userId) {
        return $query->whereHas('participants', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function chatMessages() {
        return $this->hasMany(ChatMessage::class, 'shop_id');
    }
}
