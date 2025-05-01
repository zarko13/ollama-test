<?php

namespace App\Repositories\Embedding;

use App\Models\Embedding;
use Pgvector\Laravel\Distance;

class EmbeddingRepository
{

    public static function getSimilarEmbeddings($vector){
        return Embedding::query()->nearestNeighbors('embedding', $vector, Distance::Cosine)->take(5)->get();
    }



}
