<?php

namespace App\Repositories\Ollama;

use App\Models\Chat;
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

    public static function sendChatMessage(Chat $chat){

        $api = new self();
        $fullUrl = $api->apiUrl . 'chat';

        $data = [
            'model' => $api->model,
            'messages' => $chat->getFormattedMessageHistory(),
            "stream" => false,
        ];

        Log::info('Ollama send chat request', $data);

        $response = Http::post($fullUrl, $data);

        return self::processResponse($response, false);
    }

    public static function generateEmbeddings($chunks){

        $api = new self();
        $fullUrl = $api->apiUrl . 'embed';

        $data = [
            'model' => $api->model,
            'input' => $chunks,
        ];


        $response = Http::timeout(600)->post($fullUrl, $data);

        return self::processResponse($response, false);
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
