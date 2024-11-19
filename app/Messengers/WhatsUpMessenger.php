<?php

namespace App\Messengers;

use App\Enums\MessengerTypesEnum;

class WhatsUpMessenger extends AbstractMessenger
{
	public function __construct(int $licenseId)
	{
		$this->licenseId = $licenseId;
		$this->type = MessengerTypesEnum::WHATSAPP->value;
	}
}