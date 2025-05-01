<?php

namespace App\Http\Controllers;

use App\Enums\Bot\Model;
use App\Http\Requests\DeleteInstructionRequest;
use App\Http\Requests\StoreBotRequest;
use App\Http\Requests\StoreInstructionRequest;
use App\Models\Bot;
use App\Models\Instruction;
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
        return Inertia::render('Bot/Show', $data);
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

    public function addInstruction($id){
        $bot = Bot::findOrFail($id);

        $data = [
            'bot' => $bot,
        ];

        return Inertia::render('Bot/AddInstruction', $data);
    }

    public function asyncStoreInstruction(StoreInstructionRequest $request){
        $name = $request->input('name');
        $content = $request->input('content');
        $bot = Bot::findOrFail($request->input('bot_id'));

        $response = BotService::storeInstruction($bot, $name, $content);

        return $response->toAsyncResponse();
    }

    public function asyncDeleteInstruction(DeleteInstructionRequest $request){
        $instruction = Instruction::findOrFail($request->input('reference'));

        $response = BotService::deleteInstruction($instruction);

        return $response->toAsyncResponse();
    }

    public function addDocument($id){
        $bot = Bot::findOrFail($id);

        $data = [
            'bot' => $bot,
        ];

        return Inertia::render('Bot/AddDocument', $data);
    }


}
