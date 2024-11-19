<?php

namespace App\Messengers;

use App\Enums\MessengerTypesEnum;
use App\Messengers\AbstractMessenger;

class WhatsUpMessenger extends AbstractMessenger
{
	public function __construct()
	{
		parent::__construct();

		static::$type = MessengerTypesEnum::WHATSAPP->value;
		static::$chatId = env('CHAT_APP_ID');
	}
}