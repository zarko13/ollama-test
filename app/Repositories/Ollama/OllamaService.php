<?php

namespace App\Repositories\Ollama;

use App\Models\ChatMessage;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class OllamaService
{

    public static function sendChatMessage(ChatMessage $message){

        $errors = null;
        $data = [];

        try {

            $response = OllamaLibrary::sendChatMessage($message);
            if(!$response['success']){
                return new ServiceResponse(['Failed to send chat message'], $data);
            }


            $data['body'] = $response['body'];

        } catch (Exception $error) {
            Log::error('Failed to send chat message.Error:'.$error);
            $errors[] = 'Failed to send chat message';
        }

        return new ServiceResponse($errors, $data);

    }



}
