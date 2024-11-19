<?php

namespace App\Messengers\Contracts;

interface MessengerInterface
{
	/**
	 * @return int
	 */
	public static function getLicenseId(): int;
	/**
	 * @return string
	 */
	public static function getType(): string;

	/**
	 * @return string
	 */
	public static function getChatId(): string;

	/**
	 * @param  string  $field
	 * @return string
	 */
	public static function getChatAppTokenField(string $field): string;
}