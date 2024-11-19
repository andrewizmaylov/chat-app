<?php

namespace App\Models;

use App\Messengers\Contracts\MessengerInterface;
use App\Services\ChatAppService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAppToken extends Model
{
    use HasFactory;

	protected $primaryKey = 'cabinetUserId';
	public $incrementing = false;

	protected $fillable = [
		'cabinetUserId','accessToken','accessTokenEndTime','refreshToken','refreshTokenEndTime'
	];

	/**
	 * Update or create a ChatAppToken entry.
	 *
	 * @param  object  $result
	 * @return self
	 */
	public static function updateOrCreateToken(object $result): self
	{
		return self::updateOrCreate(
			['cabinetUserId' => $result->data->cabinetUserId],
			[
				'accessToken' => $result->data->accessToken,
				'accessTokenEndTime' => $result->data->accessTokenEndTime,
				'refreshToken' => $result->data->refreshToken,
				'refreshTokenEndTime' => $result->data->refreshTokenEndTime,
			]
		);
	}

	/**
	 * @param  MessengerInterface  $messenger
	 * @return void
	 * @throws GuzzleException
	 */
	public static function checkTokenExistsAndValid(MessengerInterface $messenger): void
	{
		if (!self::where('cabinetUserId', env('CHAT_APP_CABINET_ID'))->exists()) {
			ChatAppService::getTokens();
		}

		if (time() > $messenger->getChatAppTokenField('accessTokenEndTime')) {
			$client = new Client();

			try {
				$response = $client->post(
					'https://api.chatapp.online/v1/tokens/refresh',
					[
						'headers' => [
							'Lang' => 'en',
							'Refresh' => $messenger->getChatAppTokenField('refreshToken'),
							'Content-Type' => 'application/json',
						],
					]
				);

				$body = $response->getBody();
				self::updateOrCreateToken(json_decode((string) $body));
			} catch (\Exception $e) {
				ChatAppService::logError($e);
			}

		}
	}
}
