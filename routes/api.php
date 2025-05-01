<?php

use App\Repositories\Ollama\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/message', function (Request $request){
    $response = OllamaService::sendMessage($request->input('message'));

    return $response->toAPIResponse();
});
