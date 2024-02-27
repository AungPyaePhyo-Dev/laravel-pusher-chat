<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        if(auth()->user()->is_admin == 1) {
            $users = User::with('chats', 'messages', 'chatParticipants')->where('id', '!=', auth()->user()->id)->where('is_admin', 0)->get();
        } else {
            $users = User::with('chats', 'messages', 'chatParticipants')->where('id', '!=', auth()->user()->id)->where('is_admin', '=', 1)->get();
        }
        
        return $this->success($users);
    }

    public function getChats($userId) {
        dd($userId);
    }
}
