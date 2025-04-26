<?php

namespace App\Repositories\Ollama;

use App\Models\Chat;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class OllamaService
{

    public static function sendChatMessage(Chat $chat){

        $errors = null;
        $data = [];

        try {

            $response = OllamaLibrary::sendChatMessage($chat);
            if(!$response['success']){
                return new ServiceResponse(['Failed to send chat message'], $data);
            }


            $data['message'] = $response['body']['message']['content'];

        } catch (Exception $error) {
            Log::error('Failed to send chat message.Error:'.$error);
            $errors[] = 'Failed to send chat message';
        }

        return new ServiceResponse($errors, $data);

    }



}
