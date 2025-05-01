<?php

namespace App\Repositories\Bot;

use App\Enums\Bot\Model;
use App\Models\Bot;
use App\Models\Instruction;
use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class BotService
{

    public static function storeBot(string $name, Model $model){

        $errors = null;
        $data = [];

        try {


            $bot = Bot::create([
                'name' => $name,
                'model' => $model
            ]);


            $data['bot'] = $bot;

        } catch (Exception $error) {
            Log::error('Failed to store bot.Error:'.$error);
            $errors[] = 'Failed to store bot';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function storeInstruction(Bot $bot, string $name, string $content){

        $errors = null;
        $data = [];

        try {


            $instruction = Instruction::create([
                'name' => $name,
                'content' => $content,
                'bot_id' => $bot->id
            ]);


            $data['instruction'] = $instruction;

        } catch (Exception $error) {
            Log::error('Failed to store instruction.Error:'.$error);
            $errors[] = 'Failed to store instruction';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function deleteInstruction(Instruction $instruction){

        $errors = null;
        $data = [];

        try {

            $instruction->forceDelete();

            $data['success'] = true;

        } catch (Exception $error) {
            Log::error('Failed to delete instruction.Error:'.$error);
            $errors[] = 'Failed to delete instruction';
        }

        return new ServiceResponse($errors, $data);

    }



}
