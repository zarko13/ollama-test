<?php

namespace App\Http\Controllers;

use App\Enums\Bot\Model;
use App\Http\Requests\StoreBotRequest;
use App\Models\Bot;
use App\Repositories\Bot\BotService;
use App\Repositories\ServiceResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BotController extends BaseAppController
{
    public function index(){
        return Inertia::render('Bot/Index');
    }

    public function create(){
        return Inertia::render('Bot/Create', ['models' => Model::getOptions()]);
    }

    public function show($id){
        $bot = Bot::findOrFail($id);

        $data = [
            'bot' => $bot,
            'instructions' => $bot->instructions()->oldestById()->get(),
            'documents' => $bot->documents()->oldestById()->get()
        ];
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

    public function asyncStoreBot(StoreBotRequest $request){

        $name = $request->input('name');
        $model = Model::from($request->input('model'));

        $response = BotService::storeBot($name, $model);

        return $response->toAsyncResponse();
    }


}
