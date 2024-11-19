<?php

namespace App\Http\Controllers;

use App\Enums\MessengerTypesEnum;
use App\Jobs\SendMessageJob;
use App\Messengers\WhatsUpMessenger;
use App\Models\MessageQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
	/**
	 * @return Response
	 */
	public function sendMessage(): Response
	{
		return Inertia::render('Message/CreateMessage');
	}

	public function sentQueues(): Response
	{
		return Inertia::render('Message/SentQueues', [
			'queues' => MessageQueue::orderBy('created_at', 'desc')->paginate(40),
		]);
	}

	public function queueDetails(): Response
	{
		return Inertia::render('Message/QueueDetails', [
			'queue' => MessageQueue::with('messages')->find(request('id')),
		]);
	}

	/**
	 * @param  Request  $request

	 * @throws \Throwable
	 */
	public function createMessageQueue(Request $request)
	{
		$validated = Validator::make($request->all(), [
			'message' => 'required',
			'numbers' => 'required|array|min:1',
		])->validateWithBag('message.create_message_queue');

		$queue_id = MessageQueue::create($validated)->id;

		foreach ($validated['numbers'] as $number) {
			SendMessageJob::dispatch(new WhatsUpMessenger(
				env('CHAT_APP_LICENSE'),
				env('CHAT_APP_ID')
			), $validated['message'], $number, $queue_id)
				->delay(now()->addSeconds(rand(1, 3)));
		}

		return request()->wantsJson()
			? new \Symfony\Component\HttpFoundation\JsonResponse(['message' => 'Messages are being processed in a batch', 'batch' => $queue_id], 200)
			: back()->with('status', 'created');

	}
}
