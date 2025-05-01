<?php

namespace App\Http\Controllers;

use App\Enums\Bot\Model;
use App\Models\Bot;
use App\Repositories\ServiceResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BotController extends BaseAppController
{
    public function index(){
        return Inertia::render('Bot/Index');
    }

    public function create(){
        return Inertia::render('Not/Create', ['models' => Model::getOptions()]);
    }

    public function show(Bot $bot){
        return Inertia::render('Bot/Show');
    }

    public function asyncBots(Request $request){
        $query = Bot::latestById();

        if($request->filled('name')){
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $response = new ServiceResponse(null, ['bots' => $query->paginate()]);

        return $response->toAsyncResponse();
    }

    
}
