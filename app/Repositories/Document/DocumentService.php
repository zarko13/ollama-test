<?php

namespace App\Repositories\Document;

use App\Models\Document;
use App\Repositories\Embedding\EmbeddingService;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentService
{

    public static function generateEmbeddings(){

        $errors = null;
        $data = [];

        try {

            $documents = DocumentRepository::getNotEmbeddedDocuments();
            foreach ($documents as $document) {
                EmbeddingService::generateEmbeddingsForDocument($document);
            }



            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to generate embeddings.Error:'.$error);
            $errors[] = 'Failed to generate embeddings';
        }

        return new ServiceResponse($errors, $data);

    }



}
