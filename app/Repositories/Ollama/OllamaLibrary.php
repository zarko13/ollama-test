<?php

namespace App\Repositories\Ollama;

use App\Models\ChatMessage;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaLibrary
{

    public string $apiUrl;
    public string $model;

    public function __construct(){
        $this->apiUrl = config('ollama.api_url');
        $this->model = config('ollama.model');
    }

    public static function sendChatMessage(ChatMessage $message){

        $api = new self();
        $fullUrl = $api->apiUrl . 'chat';

        $data = [
            'model' => $api->model,
            'messages' => $message->chat->getFormattedMessageHistory(),
            "stream" => false,
        ];

        Log::info('Ollama send chat request', $data);

        $response = Http::post($fullUrl, $data);

        return self::processResponse($response);
    }

    private static function processResponse($response, $log = true){
        if($log){
            Log::info('Ollama library response:'. $response->body());
        }

        return [
            'body' => $response->json(),
            'status' => $response->status(),
            'success' => $response->successful()
        ];
    }



}
