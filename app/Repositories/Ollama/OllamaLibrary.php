<?php

namespace App\Repositories\Ollama;

use App\Models\Chat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaLibrary
{

    public string $apiUrl;
    public string $embeddingModel;

    public function __construct(){
        $this->apiUrl = config('ollama.api_url');
        $this->embeddingModel = config('ollama.embedding_model');
    }

    public static function sendChatMessage(Chat $chat, $context = null){

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $api = new self();
        $fullUrl = $api->apiUrl . 'chat';


        $messages =  $chat->formatForChat();
        if($context){
            $messages[] = [
                'role' => 'system',
                'content' => 'Za genrisanje odgovora najprije pokušaj iskoristiti ovaj kontekst:' . $context
            ];
        }

        $data = [
            'model' => $chat->bot->model->value,
            'messages' => $chat->formatForChat(),
            "stream" => false,
        ];

        Log::info('Ollama send chat request', $data);

        $response = Http::timeout(600)->post($fullUrl, $data);

        return self::processResponse($response, true);
    }

    public static function generateEmbeddings($chunks){

        $api = new self();
        $fullUrl = $api->apiUrl . 'embed';

        $data = [
            'model' => $api->embeddingModel,
            'input' => $chunks,
        ];


        $response = Http::timeout(600)->post($fullUrl, $data);

        return self::processResponse($response, false);
    }

    public static function generateEmbedding($text){

        $api = new self();
        $fullUrl = $api->apiUrl . 'embed';

        $data = [
            'model' => $api->embeddingModel,
            'input' => $text,
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
