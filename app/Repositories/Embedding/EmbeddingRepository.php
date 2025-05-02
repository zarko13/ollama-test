<?php

namespace App\Repositories\Embedding;

use App\Models\Bot;
use App\Models\Embedding;
use Pgvector\Laravel\Distance;

class EmbeddingRepository
{

    public static function getEmbeddingNeighborsByCosineDistance($embedding, Bot $bot){
        return  Embedding::where('bot_id', $bot->id)
                            ->nearestNeighbors('embedding', $embedding, Distance::Cosine)
                            //->select('document_id', 'index')
                            ->take(config('ollama.number_of_neighbors'))
                            ->get();

    }

    public static function getEmbeddingNeighborsByLTwoDistance($embedding, Bot $bot){
        return  Embedding::where('bot_id', $bot->id)
                            ->nearestNeighbors('embedding', $embedding, Distance::L2)
                            //->select('document_id', 'index')
                            ->take(config('ollama.number_of_neighbors'))
                            ->get();
    }

    public static function getChunksReferencesByDocumentIdAndIndexes($documentId, $indexes){
        return  Embedding::where('document_id', $documentId)
                            ->whereIn('index', $indexes)
                            ->pluck('id')
                            ->toArray();
    }

    public static function getEmbeddingsByReferences($references){
        return  Embedding::whereIn('id', $references)
                            ->orderBy('id', 'asc')
                            ->get();
    }



}
