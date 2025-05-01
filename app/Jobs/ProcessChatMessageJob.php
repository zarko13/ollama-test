<?php

namespace App\Jobs;

use App\Models\ChatMessage;
use App\Repositories\Chat\ChatService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessChatMessageJob implements ShouldQueue
{
    use Queueable;
    public $timeout = 0;

    public ChatMessage $message;
    public function __construct(ChatMessage $chatMessage){
        $this->message = $chatMessage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);

            ChatService::processNewChatMessage($this->message)->returnOrFail();


        } catch (Exception $e) {
            throw $e;
        }
    }
}
