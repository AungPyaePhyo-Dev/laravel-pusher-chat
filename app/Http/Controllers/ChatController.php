<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChatRequest $request)
    {
        $data = $request->validated();

        $isPrivate = 1;
        if($request->has('is_private')) {
            $isPrivate = (int)$data['is_private'];
        }

        $chats = Chat::where('is_private', $isPrivate)
                    ->hasParticipant(auth()->user()->id)
                    ->whereHas('messages')
                    ->with('lastMessage.user', 'participants.user')
                    ->get();

        return response()->json([
            'chats' => $chats,
            'success' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $data = $this->prepareStoreData($request);

        if($data['userId'] === $data['otherUserId']) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot send chat to your own'
            ]);
        }

        $previousChat = $this->getPreviousChat($data['otherUserId']);

        if($previousChat === null) {
            $chat = Chat::create($data['data']);
            $chat->participants()->createMany([
                    [
                        'user_id' => $data['userId']
                    ],
                    [
                        'user_id' => $data['otherUserId']
                    ]
                ]);
            $chat->refresh()->load('lastMessage.user', 'participants.user');
            
            return response()->json([
                'chat' => $chat,
                'success' => true,
            ], 200);
        }

        return response()->json([
            'chat' => $previousChat->load('lastMessage.user', 'participants.user'),
            'success' => true,
        ], 200);
    }

    private function getPreviousChat(int $otherUserId) {
        $userId = auth()->user()->id;
        
        return Chat::where('is_private', 1)
                ->whereHas('participants', function($query) use($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereHas('participants', function($query) use($otherUserId) {
                    $query->where('user_id', $otherUserId);
                })
                ->first();
    }

    private function prepareStoreData(StoreChatRequest $request) {
        $data = $request->validated();
        $otherUserId = (int)$data['user_id'];
        unset($data['user_id']);
        $data['created_by'] = auth()->user()->id;

        return [
            'otherUserId' => $otherUserId,
            'userId' => auth()->user()->id,
            'data' => $data
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $chat->load('user', 'lastMessage.user', 'participants.user', 'participants.chat');
        return response()->json([
            'chat' => $chat
        ]);
        // return $this->success($chat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getRelatedChat(Request $request) {
        $chat = Chat::whereHas('participants', function ($query) use ($request) {
            $query->where('user_id', $request->loggedin_user_id);
        })
        ->whereHas('participants', function ($query) use ($request) {
            $query->where('user_id', $request->current_user_id);
        })
        ->with('participants') // Eager load participants if needed
        ->first();

        return response()->json($chat);   
    }

    public function getAllChats() 
    {
        return Chat::pluck('id')->toArray();
    }
}
