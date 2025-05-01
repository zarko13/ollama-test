<?php

namespace App\Repositories\Chat;

use App\Events\ChatMessageCreated;
use App\Models\Bot;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Repositories\Ollama\OllamaService;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{

    public static function storeChat(Bot $bot, string $name){

        $errors = null;
        $data = [];

        try {


            $chat = Chat::create([
                'name' => $name,
                'user_id' => $bot->id,
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

    public static function storeUserChatMessage(Chat $chat, $message){

        $errors = null;
        $data = [];

        try {

            if(!$chat->isOpen()){
                return new ServiceResponse(['Chat is not open'], $data);
            }

            $message = ChatMessage::create([
                'chat_id' => $chat->id,
                'type' => ChatMessage::$_TYPE_BY_USER,
                'content' => $message
            ]);

            event(new ChatMessageCreated($message));

            $data['success'] = true;
        } catch (Exception $error) {
            DB::rollBack();
            Log::error('Failed to store user chat message.Error:'.$error);
            $errors[] = 'Failed to store chat message';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function processNewChatMessage(ChatMessage $message){

        $errors = null;
        $data = [];

        try {

            if($message->type == ChatMessage::$_TYPE_BY_SYSTEM){
                return new ServiceResponse($errors, ['success' => true]);
            }

            $systemMessage = OllamaService::sendChatMessage($message->chat)->returnOrFail()->data['message'];


            $chatMessage = ChatMessage::create([
                'chat_id' => $message->chat_id,
                'type' => ChatMessage::$_TYPE_BY_SYSTEM,
                'content' => $systemMessage
            ]);

            event(new ChatMessageCreated($chatMessage));

            $data['success'] = true;
        } catch (Exception $error) {
            Log::error('Failed to process new chat message.Error:'.$error);
            $errors[] = 'Failed to process new chat message';
        }

        return new ServiceResponse($errors, $data);

    }

}
