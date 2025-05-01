<?php

namespace App\Repositories\Bot;

use App\Enums\Bot\Model;
use App\Models\Bot;
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



}
