<?php

namespace App\Repositories\Embedding;


use App\Models\Document;
use App\Models\Embedding;
use App\Repositories\Ollama\OllamaService;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmbeddingService
{

    public static function generateEmbeddingsForDocument(Document $document){

        $errors = null;
        $data = [];

        try {


            $chunks = self::chunkDocument($document)->returnOrFail()->data['chunks'];
            $embeddings = OllamaService::generateEmbeddings($document->bot, $chunks)->returnOrFail()->data['embeddings'];

            DB::beginTransaction();

            for ($i=0; $i < count($chunks); $i++) {
                Embedding::create([
                    'document_id' => $document->id,
                    'type' => Embedding::$_TYPE_TEXT,
                    'bot_id' => $document->bot->id,
                    'index' => $i,
                    'embedding' => $embeddings[$i],
                    'metadata' => [
                        'content' => $chunks[$i]
                    ]
                ]);
            }


            $document->update([
                'status' => Document::$_STATUS_PROCESSED
            ]);

            DB::commit();

            $data['success'] = true;

        } catch (Exception $error) {
            DB::rollBack();
            Log::error('Failed to generate embeddings for document.Error:'.$error);
            $errors[] = 'Failed to generate embeddings for document';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function chunkDocument(Document $document, int $chunkSize = 600, int $overlapSize = 100){

        $errors = null;
        $data = [];
        $chunks = [];

        try {

            $text = Storage::get($document->path);


            $textLength = strlen($text);

            for ($start = 0; $start < $textLength; $start += ($chunkSize - $overlapSize)) {
                if ($start + $chunkSize > $textLength) {
                    $chunks[] = substr($text, $start);
                    break;
                }
                $chunks[] = mb_convert_encoding(substr($text, $start, $chunkSize), 'UTF-8');
            }





            $data['chunks'] = $chunks;

        } catch (Exception $error) {
            Log::error('Failed to chunk document.Error:'.$error);
            $errors[] = 'Failed to chunk document document';
        }

        return new ServiceResponse($errors, $data);

    }

}
