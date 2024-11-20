<?php

namespace App\Http\Controllers;

use App\FileProcessor\FileProcessor;
use App\Jobs\SendMessageJob;
use App\Messengers\WhatsUpMessenger;
use App\Models\MessageQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
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
	 * @throws \Throwable
	 */
	public function createMessageQueue(Request $request): JsonResponse|RedirectResponse
	{
		$validated = Validator::make($request->all(), [
			'message' => 'required',
			'numbers' => 'required|array|min:1',
		])->validateWithBag('message.create_message_queue');

		$queue_id = MessageQueue::create($validated)->id;

		$jobs = [];

		foreach ($validated['numbers'] as $phone) {
			// Add each job to the jobs array
			$jobs[] = new SendMessageJob(
				new WhatsUpMessenger(env('CHAT_APP_LICENSE')),
				$validated['message'],
				$phone,
				$queue_id
			);
		}

		// Dispatch the jobs as a batch
		Bus::batch($jobs)
			->then(function ($batch) {
				// This callback runs after the batch is completed
				Log::info('Batch completed successfully.');
			})
			->catch(function ($batchException) {
				// This callback runs if any of the jobs in the batch fail
				Log::error('Batch failed: ' . $batchException->getMessage());
			})
			->dispatch();

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
