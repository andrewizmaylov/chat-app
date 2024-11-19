<?php

namespace App\Messengers\Contracts;

interface MessengerInterface
{
	/**
	 * @return int
	 */
	public function getLicenseId(): int;
	/**
	 * @return string
	 */
	public function getType(): string;

	/**
	 * @param  string  $field
	 * @return string
	 */
	public function getChatAppTokenField(string $field): string;
}