<?php

namespace App\Console\Commands;

use App\Repositories\Embedding\EmbeddingRepository;
use App\Repositories\Ollama\OllamaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $embedding = OllamaService::submitMessage('calming oat-based powder exfoliant')->returnOrFail()->data['embedding'];
        $neighbors = EmbeddingRepository::getSimilarEmbeddings($embedding);
        foreach ($neighbors as $neighbor) {
            Log::info($neighbor->neighbor_distance);
            Log::info($neighbor->metadata['content']);
        }
    }
}
