<?php

namespace App\Services;

use App\Messengers\Contracts\MessengerInterface;
use App\Models\ChatAppToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ChatAppService
{
	/**
	 * Get and preserve Access and Refresh Tokens
	 *
	 * @return void
	 * @throws GuzzleException
	 */
	public function getTokens(): void
	{
		$client = new Client();
		try {
			$response = $client->post(
				'https://api.chatapp.online/v1/tokens',
				[
					'headers' => [
						'Content-Type' => 'application/json',
					],
					'json' => [
						'email' => env('CHAT_APP_EMAIL'), // email from personal account
						'password' => env('CHAT_APP_PASSWORD'), // password from personal account
						'appId' => env('CHAT_APP_ID'), // appId from personal account
					],
				]
			);
			$body = $response->getBody();

			$result = json_decode($body);
			if ($result) {
				ChatAppToken::updateOrCreateToken($result);
			}
		} catch (\Exception $e) {
			$this->logError($e);
		}
	}

	/**
	 * Send message through selected Channel
	 *
	 * @param  MessengerInterface  $messenger
	 * @param  string  $message
	 * @return void
	 * @throws GuzzleException
	 */
	public function sendMessage(MessengerInterface $messenger, string $message): void
	{
		$client = new Client();
		$licenseId = $messenger::getLicenseId();
		$messengerType = $messenger::getType();
		$chatId = $messenger::getChatId(); // phone or chatId
		$accessToken = $messenger::getChatAppTokenField('accessToken');
		try {
			$response = $client->post(
				"https://api.chatapp.online/v1/licenses/$licenseId/messengers/$messengerType/chats/$chatId/messages/text",
				[
					'headers' => [
						'Authorization' => $accessToken,
						'Content-Type' => 'application/json',
					],
					'json' => [
						'text' => $message,
					],
				]
			);
			$body = $response->getBody();

			$result = json_decode((string)$body);
			if ($result) {
				// TODO: # save results to db

				print_r($result);
			}
		} catch (\Exception $e) {
			$this->logError($e);
		}
	}

	private function logError(\Exception $e): void
	{
		print_r([$e->getCode(), $e->getMessage()]);

		Log::error('An error occurred: '.$e->getMessage(), [
			'exception' => $e,
			'stack_trace' => $e->getTraceAsString(),
			'timestamp' => now(),
			'user_id' => auth()->id(),
		]);
	}
}


