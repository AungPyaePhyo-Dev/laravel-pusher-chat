<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = "chat_messages";
    protected $guarded = ['id'];

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat() {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    
    
}
