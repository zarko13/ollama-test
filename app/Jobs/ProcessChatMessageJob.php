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

            ChatService::processNewChatMessage($this->message)->returnOrFail();

            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
