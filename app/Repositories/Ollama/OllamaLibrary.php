<?php

namespace App\Repositories\Ollama;

use App\Models\Chat;
use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $history = $chat->getFormattedMessageHistory();


        $data = [
            'model' => $api->model,
            'messages' => $chat->getFormattedMessageHistory(),
            "stream" => false,
            'options' => [
                'temperature' => 0.0
            ],
            'format' => 'json'
        ];

        Log::info('Ollama send chat request', $data);

        $response = Http::timeout(600)->post($fullUrl, $data);

        return self::processResponse($response, true);
    }

    public static function sendMessage($incomingMessage){

        ini_set('max_execution_time', 0);

        $api = new self();
        $fullUrl = $api->apiUrl . 'chat';

        $message = 'Translate this message into english from spanish, without special characters, new lines or breaks. Don\'t use double-quotes. If the message is empty, return \'\'. Message: ' . $incomingMessage;


        $data = [
            'model' => $api->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            "stream" => false,
            'options' => [
                'temperature' => 0.0
            ]
        ];

        Log::info('Ollama send chat request', $data);

        $response = Http::timeout(600)->connectTimeout(600)->post($fullUrl, $data);

        return self::processResponse($response, true);
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

    public static function generateEmbedding($content){

        $api = new self();
        $fullUrl = $api->apiUrl . 'embed';

        $data = [
            'model' => $api->model,
            'input' => $content,
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
