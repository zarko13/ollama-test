<?php

namespace App\Console\Commands;

use App\Events\ChatMessageCreated;
use App\Models\ChatMessage;
use App\Models\Embedding;
use App\Repositories\Ollama\OllamaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Pgvector\Laravel\Distance;

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
        $embedding = OllamaService::generateEmbedding('Uslovi aromaticonsti molekule')->returnOrFail()->data['embedding'];
        $neighbors = Embedding::query()->nearestNeighbors('embedding', $embedding, Distance::Cosine)->take(5)->get();
        $context = [];
       foreach ($neighbors as $neighbor) {
            Log::info($neighbor->neighbor_distance);
            Log::info($neighbor->metadata['content']);
       }

    }
}
