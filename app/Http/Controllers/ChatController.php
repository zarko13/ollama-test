<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\Repositories\Chat\ChatService;
use App\Repositories\ServiceResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends BaseAppController
{
    public function index(){
        return Inertia::render('Chat/Index');
    }

    public function create(){
        return Inertia::render('Chat/Create');
    }

    public function show($id){
        $chat = $this->user->chats()->findOrFail($id);

        $data = [
            'chat' => $chat,
            'messages' => $chat->messages()->oldestById()->get()
        ];
        return Inertia::render('Chat/Show', $data);
    }

    public function asyncChats(Request $request){
        $query = Chat::latestById();

        if($request->filled('name')){
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $response = new ServiceResponse(null, ['chats' => $query->paginate()]);

        return $response->toAsyncResponse();
    }

    public function asyncStoreChat(StoreChatRequest $request){
        $name = $request->input('name');

        $response = ChatService::storeChat($this->user, $name);

        return $response->toAsyncResponse();
    }

    public function asyncCloseChat(StoreChatRequest $request){
        $chat = $this->user->chats()->findOrFail($request->input('reference'));

        $response = ChatService::closeChat($chat);

        return $response->toAsyncResponse();
    }

    public function asyncStoreMessage(StoreMessageRequest $request){
        $chat = $this->user->chats()->findOrFail($request->input('reference'));
        $message = $request->input('message');

        $response = ChatService::storeUserChatMessage($chat, $message);

        return $response->toAsyncResponse();
    }


}
