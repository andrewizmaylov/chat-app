<?php

namespace App\Enums;

enum MessengerTypesEnum: string
{
	case WHATSAPP = 'grWhatsApp';
	case TELEGRAM = 'telegram';
	case VIBER = 'viber';
	case AVITO = 'avito';
}
