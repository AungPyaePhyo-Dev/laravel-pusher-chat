<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
}
