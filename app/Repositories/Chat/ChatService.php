<?php

namespace App\Repositories\Chat;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\Ollama\OllamaService;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{

    public static function storeChat(User $user, string $name){

        $errors = null;
        $data = [];

        try {


            $chat = Chat::create([
                'name' => $name,
                'user_id' => $user->id,
                'status' => Chat::$_STATUS_OPEN
            ]);


            $data['chat'] = $chat;

        } catch (Exception $error) {
            Log::error('Failed to store chat.Error:'.$error);
            $errors[] = 'Failed to store chat';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function closeChat(Chat $chat){

        $errors = null;
        $data = [];

        try {


            $chat->update([
                'status' => Chat::$_STATUS_CLOSED
            ]);


            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to close chat.Error:'.$error);
            $errors[] = 'Failed to close chat';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function storeChatMessage(Chat $chat, $message, $type){

        $errors = null;
        $data = [];

        try {

            if($type == ChatMessage::$_TYPE_BY_USER && !$chat->isOpen()){
                return new ServiceResponse(['Chat is not open'], $data);
            }

            DB::beginTransaction();

            ChatMessage::create([
                'chat_id' => $chat->id,
                'type' => $type,
                'content' => $message
            ]);

            $message = OllamaService::sendChatMessage($chat)->returnOrFail()->data['message'];

            ChatMessage::create([
                'chat_id' => $chat->id,
                'type' => ChatMessage::$_TYPE_BY_SYSTEM,
                'content' => $message
            ]);

            DB::commit();

            $data['message'] = $message;
        } catch (Exception $error) {
            DB::rollBack();
            Log::error('Failed to store chat message.Error:'.$error);
            $errors[] = 'Failed to store chat message';
        }

        return new ServiceResponse($errors, $data);

    }

}
