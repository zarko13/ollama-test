<?php

namespace App\Repositories\Document;

use App\Models\Document;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentService
{

    public static function storeDocument(string $name, $file){

        $errors = null;
        $data = [];

        try {

            $path = Storage::put('documents', $file);


            $document = Document::create([
                'name' => $name,
                'path' => $path,
                'status' => Document::$_STATUS_PENDING
            ]);


            $data['document'] = $document;

        } catch (Exception $error) {
            Log::error('Failed to store document.Error:'.$error);
            $errors[] = 'Failed to store document';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function deleteDocument(Document $document){

        $errors = null;
        $data = [];

        try {


            $document->delete();


            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to delete document.Error:'.$error);
            $errors[] = 'Failed to delete document';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function generateEmbeddings(){

        $errors = null;
        $data = [];

        try {

            $documents = DocumentRepository::getNotEmbeddedDocuments();
            foreach ($documents as $document) {
                self::generateEmbeddingsForDocument($document);
            }



            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to generate embeddings.Error:'.$error);
            $errors[] = 'Failed to generate embeddings';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function generateEmbeddingsForDocument(Document $document){

        $errors = null;
        $data = [];

        try {





            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to generate embeddings for document.Error:'.$error);
            $errors[] = 'Failed to generate embeddings for document';
        }

        return new ServiceResponse($errors, $data);

    }

}
