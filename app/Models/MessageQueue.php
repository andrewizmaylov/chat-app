<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessageQueue extends Model
{
    use HasFactory;

	protected $fillable = [
		'message', 'numbers'
	];

	protected $casts = [
		'numbers' => 'array',
		'created_at' => 'datetime:Y-m-d H:i:s',
	];

	public function messages(): HasMany
	{
		return $this->hasMany(Message::class, 'queue_id');
	}
}
