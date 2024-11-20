<?php

namespace App\FileProcessor\Contracts;

use Illuminate\Http\JsonResponse;

interface FileProcessInterface
{
	public function reedFromFile(string $file_path): ?JsonResponse;
}