<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('documents', [DocumentController::class, 'index']);
Route::get('document', [DocumentController::class, 'create']);
Route::get('async/documents', [DocumentController::class, 'asyncDocuments']);


Route::get('chats', [ChatController::class, 'index']);
Route::get('chat', [ChatController::class, 'create']);
Route::get('chat/{id}', [ChatController::class, 'show']);
Route::get('async/chats', [ChatController::class, 'asyncChats']);
Route::post('async/store-chat', [ChatController::class, 'asyncStoreChat']);
Route::post('async/close-chat', [ChatController::class, 'asyncCloseChat']);
Route::post('async/store-message', [ChatController::class, 'asyncStoreMessage']);

Route::get('bots', [BotController::class, 'index']);
Route::get('bot', [BotController::class, 'create']);
Route::get('bot/{id}', [BotController::class, 'show']);
Route::get('async/bots', [BotController::class, 'asyncBots']);
Route::post('async/store-bot', [BotController::class, 'asyncStoreBot']);
Route::get('bot/{id}/add-instruction', [BotController::class, 'addInstruction']);
Route::post('async/store-instruction', [BotController::class, 'asyncStoreInstruction']);
Route::post('async/delete-instruction', [BotController::class, 'asyncDeleteInstruction']);
Route::get('bot/{id}/add-document', [BotController::class, 'addDocument']);
Route::post('async/store-document', [BotController::class, 'asyncStoreDocument']);
Route::post('async/delete-document', [BotController::class, 'asyncDeleteDocument']);
Route::get('download-document/{id}', [BotController::class, 'downloadDocument']);
