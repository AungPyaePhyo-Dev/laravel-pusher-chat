<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request) {
        $data = $request->validated();

        $participantId = $data['participant_id'];

        $chatId = $data['chat_id'];
        
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        // $messages = ChatMessage::where('chat_id', $chatId)
        //             ->with('user')
        //             ->latest('created_at')
        //             ->simplePaginate(
        //                 $pageSize,
        //                 ['*'],
        //                 'page',
        //                 $currentPage
        //             );

        // return $this->success($messages->getCollection());

        $messages = ChatMessage::where('chat_id', $chatId)
                                ->where(function($qry)  use($participantId){
                                    $qry->where('user_id', auth()->user()->id)
                                        ->orWhere('user_id', $participantId);
                                })
                                ->with('user')
                                ->orderBy('id', 'asc')
                                ->get();
                                // ->groupBy(function ($message) {
                                //     return $message->created_at->format('Y-m-d'); // Group by date (Y-m-d)
                                // });

        return response()->json([
            'chats' => $messages,
            'success' => true,
        ], 200);
    }

    public function store(StoreMessageRequest $request) {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->load('user', 'chat', 'chat.lastMessage', 'chat.participants', 'chat.participants.user');

        // TODO send broadcast event to pusher and send notification to onesingle service
        $this->sendNotificationToOther($chatMessage);

        return response()->json([
            'messages' => $chatMessage,
            'status' => true
        ], 200);

        // return $this->success($chatMessage, 'Message has been sent successfully');
    }

    private function sendNotificationToOther(ChatMessage $chatMessage) 
    {
        // $chatId = $chatMessage->id;

        broadcast(new NewMessageSent($chatMessage))->toOthers();

        // dispatch(new SendMessage($chatMessage));

        // $user = auth()->user();
        // $userId = $user->id;

        // $chat = Chat::where('id', $chatMessage->chat_id)
        //         ->with(['participants' => function($query) use ($userId) {
        //             $query->where('user_id', '!=', $userId);
        //         }])
        //         ->first();        

        // if(count($chat->participants) > 0) {
        //     $otherUserId = $chat->participants[0]->user_id;

        //     $otherUser = User::where('id', $otherUserId)->first();
        //     $otherUser->sendNewMessageNotification([
        //         'messageData' => [
        //             'senderName' => $user->username,
        //             'message' => $chatMessage->message,
        //             'chatId' => $chatMessage->chat_id
        //         ]
        //     ]);
        // }

    }
}
