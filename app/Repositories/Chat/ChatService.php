<?php

namespace App\Repositories\Chat;

use App\Models\Chat;
use App\Models\User;
use App\Repositories\ServiceResponse;
use Exception;
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

}
