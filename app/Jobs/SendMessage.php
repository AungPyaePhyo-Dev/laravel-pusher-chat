<?php

namespace App\Jobs;

use App\Events\NewMessageSent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $chatMessage;
    public function __construct($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new NewMessageSent($this->chatMessage))->toOthers();
    }
}
