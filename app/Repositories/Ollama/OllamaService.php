<?php

namespace App\Repositories\Ollama;

use App\Models\Bot;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class OllamaService
{

    public static function sendChatMessage(Chat $chat, $context = null){

        $errors = null;
        $data = [];

        try {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);


            $response = OllamaLibrary::sendChatMessage($chat, $context);
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

    public static function generateEmbeddings($chunks){

        $errors = null;
        $data = [];

        try {

            $response = OllamaLibrary::generateEmbeddings($chunks);
            if(!$response['success'] || !isset($response['body']['embeddings'])){
                return new ServiceResponse(['Failed to generate embeddings'], $data);
            }


            $data['embeddings'] = $response['body']['embeddings'];

        } catch (Exception $error) {
            Log::error('Failed to generate embeddings.Error:'.$error);
            $errors[] = 'Failed to generate embeddings';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function generateEmbedding($text){

        $errors = null;
        $data = [];

        try {

            $response = OllamaLibrary::generateEmbedding($text);
            if(!$response['success'] || !isset($response['body']['embeddings'])){
                return new ServiceResponse(['Failed to generate embedding'], $data);
            }


            $data['embedding'] = $response['body']['embeddings'][0];

        } catch (Exception $error) {
            Log::error('Failed to generate embedding.Error:'.$error);
            $errors[] = 'Failed to generate embedding';
        }

        return new ServiceResponse($errors, $data);

    }



}
