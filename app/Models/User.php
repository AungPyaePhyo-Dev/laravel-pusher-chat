<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\MessageSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const USER_TOKEN = "userToken";

    protected $table = "users";
    protected $guarded = ['id'];
    protected $hidden = [
        'password'
    ];

    public function chats() {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function messages() {
        return $this->hasMany(ChatMessage::class, 'user_id');
    }

    public function routeNotificationForOneSignal()
    { 
        return ['tags' => ['key' => 'userId', 'relation' => ':', 'value' => (string)(1)]];
    }
    public function sendNewMessageNotification($data) {
        $this->notify(new MessageSent($data));
    }
}
