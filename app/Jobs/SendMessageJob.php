<?php

namespace App\Jobs;

use App\Messengers\Contracts\MessengerInterface;
use App\Services\ChatAppService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;


	private MessengerInterface $messenger;
	private string $message;
	private string $chatId;
	private int $queue_id;

	/**
	 * Create a new job instance.
	 *
	 * @param  MessengerInterface  $messenger
	 * @param  string  $message
	 * @param  string  $chatId
	 * @param  int  $queue_id
	 */
	public function __construct(MessengerInterface $messenger, string $message, string $chatId, int $queue_id)
	{
		$this->messenger = $messenger;
		$this->message = $message;
		$this->chatId = $chatId;
		$this->queue_id = $queue_id;
	}

	/**
	 * Execute the job.
	 * @throws GuzzleException
	 */
    public function handle(): void
    {
	    ChatAppService::sendMessage($this->messenger, $this->message, $this->chatId, $this->queue_id);
    }
}
