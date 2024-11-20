<?php

namespace App\FileProcessor;

use App\FileProcessor\Contracts\FileProcessInterface;
use App\FileProcessor\Processors\CSVFileProcessor;
use App\FileProcessor\Processors\DOCFileProcessor;
use App\FileProcessor\Processors\TXTFileProcessor;
use App\FileProcessor\Processors\XMLFileProcessor;
use Illuminate\Http\JsonResponse;

class FileProcessor implements FileProcessInterface
{
	private FileProcessInterface $processor;

	/**
	 * @throws \Exception
	 */
	public function __construct(string $type)
	{
		$this->processor = match($type) {
			'csv' => new CSVFileProcessor(),
			'txt' => new TXTFileProcessor(),
			'doc' => new DOCFileProcessor(),
			'xml' => new XMLFileProcessor(),

			default => throw new \Exception("Unsupported File Type: $type"),
		};
	}

	public function reedFromFile(string $file_path): ?JsonResponse
	{
		return $this->processor->reedFromFile($file_path);
	}
}