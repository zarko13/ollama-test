<?php

namespace App\Console\Commands;

use App\Events\ChatMessageCreated;
use App\Models\ChatMessage;
use App\Repositories\Ollama\OllamaService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $embedding = OllamaService::generateEmbedding('Dobar dan možete li mi pomoci')->returnOrFail()->data['embedding'];
        dd($embedding);
    }
}
