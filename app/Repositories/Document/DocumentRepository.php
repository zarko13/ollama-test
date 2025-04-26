<?php

namespace App\Repositories\Document;

use App\Models\Document;

class DocumentRepository
{

    public static function getNotEmbeddedDocuments(){
        return Document::whereIn('status', [Document::$_STATUS_PENDING, Document::$_STATUS_FAILED])->get();
    }



}
