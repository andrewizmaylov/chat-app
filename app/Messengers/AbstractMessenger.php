<?php

namespace App\Messengers;

use App\Messengers\Contracts\MessengerInterface;
use App\Models\ChatAppToken;

abstract class AbstractMessenger implements MessengerInterface
{

	protected static int $licenseId;
	protected static string $type;
	protected static string $chatId;

	public function __construct()
	{
		self::$licenseId = (int) env('CHAT_APP_LICENSE');
	}
	public static function getLicenseId(): int
	{
		return self::$licenseId;
	}

	public static function getType(): string
	{
		return static::$type;
	}

	public static function getChatId(): string
	{
		return static::$chatId;
	}

	/**
	 * @param  string  $field
	 * @return string
	 * @throws \Exception
	 */
	public static function getChatAppTokenField(string $field): string
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