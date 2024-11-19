<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

	protected $fillable = [
		'queue_id', 'chat_id', 'status', 'data'
	];

	protected $casts = [
		'data' => 'array',
		'created_at' => 'datetime:Y-m-d H:i:s',
	];
	public static function createRecord(object $result, int $queue_id): void
	{
		self::create([
			'queue_id' => $queue_id,
			'chat_id' => $result->data->chatId,
			'status' => $result->success,
			'data' => $result->data,
		]);
	}

	public function messageQueue(): BelongsTo
	{
		return $this->belongsTo(MessageQueue::class, );
	}
}
