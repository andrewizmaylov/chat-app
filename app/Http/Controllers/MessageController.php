<?php

namespace App\Http\Controllers;

use App\FileProcessor\FileProcessor;
use App\Jobs\SendMessageJob;
use App\Messengers\WhatsUpMessenger;
use App\Models\MessageQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{
	/**
	 * @return Response
	 */
	public function sendMessage(): Response
	{
		return Inertia::render('Message/CreateMessage');
	}

	/**
	 * @return Response
	 */
	public function sentQueues(): Response
	{
		return Inertia::render('Message/SentQueues', [
			'queues' => MessageQueue::orderBy('created_at', 'desc')->paginate(15),
		]);
	}

	/**
	 * @return Response
	 */
	public function queueDetails(): Response
	{
		return Inertia::render('Message/QueueDetails', [
			'queue' => MessageQueue::with('messages')->find(request('id')),
		]);
	}

	/**
	 * @param  Request  $request
	 * @return RedirectResponse|JsonResponse
	 * @throws ValidationException
	 */
	public function createMessageQueue(Request $request): JsonResponse|RedirectResponse
	{
		$validated = Validator::make($request->all(), [
			'message' => 'required',
			'numbers' => 'required|array|min:1',
		])->validateWithBag('message.create_message_queue');

		$queue_id = MessageQueue::create($validated)->id;

		foreach ($validated['numbers'] as $number) {
			SendMessageJob::dispatch(
				new WhatsUpMessenger(env('CHAT_APP_LICENSE')),
				$validated['message'],
				$number,
				$queue_id
			)->delay(now()->addSeconds(rand(5, 50)));
		}

		return request()->wantsJson()
			? new \Symfony\Component\HttpFoundation\JsonResponse(['message' => 'Messages are being processed in a batch', 'batch' => $queue_id], 200)
			: back()->with('status', 'created');
	}

	/**
	 * @throws \Exception
	 */
	public function reedFile(Request $request)
	{
		$request->validate([
			'file' => 'required|file|mimes:csv,txt,doc,xml',
		]);
		$file = $request->file('file');

		return (new FileProcessor($file->extension()))->reedFromFile($file->getRealPath());
	}
}
