<?php

namespace App\Http\Controllers;

use App\Models\Chat;
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

        return Inertia::render('Chat/Show', ['chat' => $chat]);
    }

    public function asyncChats(Request $request){
        $query = Chat::latestById();

        if($request->filled('name')){
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $response = new ServiceResponse(null, ['chats' => $query->paginate()]);

        return $response->toAsyncResponse();
    }


}
