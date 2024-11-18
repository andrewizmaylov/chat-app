<?php

namespace App\Services;

use App\Models\ChatAppToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ChatAppServise
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
			$dto = json_decode($body);
			ChatAppToken::updateOrCreate(
				['cabinetUserId' => $dto->data->cabinetUserId],
				[
					'accessToken' => $dto->data->accessToken,
					'accessTokenEndTime' => $dto->data->accessTokenEndTime,
					'refreshToken' => $dto->data->refreshToken,
					'refreshTokenEndTime' => $dto->data->refreshTokenEndTime,
				]
			);
		} catch (\Exception $e) {
			Log::error('An error occurred: '.$e->getMessage(), [
				'exception' => $e,
				'stack_trace' => $e->getTraceAsString(),
				'timestamp' => now(),
				'user_id' => auth()->id(),
			]);
		}
	}
}


