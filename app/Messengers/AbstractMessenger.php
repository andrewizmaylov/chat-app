<?php

namespace App\Messengers;

use App\Messengers\Contracts\MessengerInterface;
use App\Models\ChatAppToken;
use App\Services\ChatAppService;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractMessenger implements MessengerInterface
{

	protected ?int $licenseId = null;
	protected ?string $type = null;

	public function getLicenseId(): int
	{
		return $this->licenseId;
	}

	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param  string  $field
	 * @return string
	 * @throws \Exception
	 * @throws GuzzleException
	 */
	public function getChatAppTokenField(string $field): string
	{
		if (empty(env('CHAT_APP_CABINET_ID'))) {
			throw new \Exception('cabinetUserId field is required');
		}
		
		if (!ChatAppToken::where('cabinetUserId', env('CHAT_APP_CABINET_ID'))->exists()) {
			ChatAppService::getTokens();
		}
		$record = ChatAppToken::where('cabinetUserId', env('CHAT_APP_CABINET_ID'))->first();

		return $record->$field;
	}
}