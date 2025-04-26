<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Repositories\Document\DocumentService;
use App\Repositories\ServiceResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends BaseAppController
{

    public function index(){
        return Inertia::render('Document/Index');
    }

    public function create(){
        return Inertia::render('Document/Create');
    }

    public function asyncDocuments(Request $request){
        $query = Document::latestById();

        if($request->filled('name')){
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $response = new ServiceResponse(null, ['documents' => $query->paginate()]);

        return $response->toAsyncResponse();
    }

    public function asyncStoreDocument(StoreDocumentRequest $request){

        $name = $request->input('name');
        $file = $request->file('file');

        $response = DocumentService::storeDocument($name, $file);

        return $response->toAsyncResponse();
    }

    public function asyncDeleteDocument(DeleteDocumentRequest $request){

        $document = Document::findOrFail($request->input('reference'));

        $response = DocumentService::deleteDocument($document);

        return $response->toAsyncResponse();
    }

}
