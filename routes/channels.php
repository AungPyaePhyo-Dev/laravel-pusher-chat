<?php

use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.{id}', function ($user, $id) {

    $participant = ChatParticipant::where([
        [
            'user_id', $user->id,
        ],
        [
            'chat_id', $id
        ]
        ]);
    return $participant !== null;
});


Broadcast::channel('private-chat-{id}', function($user, $id) {
    return (int) $user->id === (int) $id;
});