<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DocumentController extends BaseAppController
{

    public function index(){
        return Inertia::render('Document/Index');
    }

    public function create()
    {
        //
    }

}
