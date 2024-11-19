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
}
