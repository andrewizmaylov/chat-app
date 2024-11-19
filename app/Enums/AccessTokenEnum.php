<?php

namespace App\Enums;

enum AccessTokenEnum: string
{
	case ACCESS_TOKEN = 'accessToken';
	case REFRESH_TOKEN = 'refreshToken';
}
