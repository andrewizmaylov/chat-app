<?php

namespace App\Messengers;

use App\Enums\MessengerTypesEnum;
use GuzzleHttp\Exception\GuzzleException;

class WhatsUpMessenger extends AbstractMessenger
{
	public function __construct(int $licenseId)
	{
		$this->licenseId = $licenseId;
		$this->type = MessengerTypesEnum::WHATSAPP->value;
	}


	/**
	 * @param  int  $licenseId
	 * @return string
	 * @throws GuzzleException
	 */
	public static function getAccessToken(int $licenseId): string
	{
		$instance = new self($licenseId); // Create an instance of the class
		return $instance->getChatAppTokenField('accessToken');
	}
}