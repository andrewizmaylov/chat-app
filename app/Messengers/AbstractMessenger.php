<?php

namespace App\Messengers;

use App\Messengers\Contracts\MessengerInterface;
use App\Models\ChatAppToken;

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
	 */
	public function getChatAppTokenField(string $field): string
	{
		if (empty(env('CHAT_APP_CABINET_ID'))) {
			throw new \Exception('cabinetUserId field is required');
		}

		$record = ChatAppToken::where('cabinetUserId', env('CHAT_APP_CABINET_ID'))->first();

		if (!$record) {
			throw new \Exception('ChatAppTokens not found');
		}

		return $record->$field;
	}
}