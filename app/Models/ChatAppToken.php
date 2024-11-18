<?php

namespace App\Models;

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
}
