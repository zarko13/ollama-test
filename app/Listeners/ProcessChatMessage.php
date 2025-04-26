<?php

namespace App\Listeners;

use App\Events\ChatMessageCreated;
use App\Jobs\ProcessChatMessageJob;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessChatMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChatMessageCreated $event): void
    {
        try {

            ProcessChatMessageJob::dispatch($event->message);

        } catch (Exception $e) {
            Log::error('Failed to process chat message.Error:' . $e);
        }
    }
}
