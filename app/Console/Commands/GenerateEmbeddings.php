<?php

namespace App\Console\Commands;

use App\Repositories\Document\DocumentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:embeddings';

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
        Log::info('Generate embeddings command called');

        DocumentService::generateEmbeddings();
        
        Log::info('Generate embeddings command finished');
    }
}
