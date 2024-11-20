<?php

namespace App\Services;

use App\Messengers\Contracts\MessengerInterface;
use App\Models\ChatAppToken;
use App\Models\Message;
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
	public static function getTokens(): void
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
			self::logError($e);
		}
	}

	/**
	 * Send message through selected Channel
	 *
	 * @param  MessengerInterface  $messenger
	 * @param  string  $message
	 * @param  string  $chatId
	 * @param  int  $queue_id
	 * @return void
	 * @throws GuzzleException
	 */
	public static function sendMessage(MessengerInterface $messenger, string $message, string $chatId, int $queue_id): void
	{
		ChatAppToken::checkTokenExistsAndValid($messenger);
		$client = new Client();
		$licenseId = $messenger->getLicenseId();
		$messengerType = $messenger->getType();
		$accessToken = $messenger->getChatAppTokenField('accessToken');
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

			Message::createRecord(json_decode((string)$body), $queue_id);
		} catch (\Exception $e) {
			self::logError($e);
		}
	}

	/**
	 * @param  string $access_token
	 * @return bool
	 * @throws GuzzleException
	 */
	public static function checkLicenseExpired(string $access_token): bool
	{
		$client = new Client();
		$response = $client->get(
			'https://api.chatapp.online/v1/licenses',
			[
				'headers' => [
					'Authorization' => $access_token,
					'Lang' => 'en',
				],
			]
		);
		$body = $response->getBody();

		$result = json_decode((string) $body);

		return $result->data[0]->status->code === 'failed';
	}

	public static function logError(\Exception $e): void
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


