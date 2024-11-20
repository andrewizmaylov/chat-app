<?php

namespace App\FileProcessor\Processors;


use App\FileProcessor\Contracts\FileProcessInterface;
use Illuminate\Http\JsonResponse;

class CSVFileProcessor implements FileProcessInterface
{

	public function reedFromFile(string $file_path): JsonResponse
	{
		$stream = fopen($file_path, 'r');
		if ($stream === false) {
			return response()->json(['error' => 'Unable to read the file.'], 400);
		}

		$headers = fgetcsv($stream);
		$phone_numbers = [];
		while (($row = fgetcsv($stream)) !== false) {
			$mappedRow = array_combine($headers, $row);
			$phone = $mappedRow['phone'];

			$phone = preg_replace('/\D/', '', $phone);  // Remove non-digits
			if (preg_match('/^\d{10,11}$/', $phone)) {
				$phone_numbers[] = $phone;
			}
		}
		fclose($stream);

		return response()->json([
			'message' => 'Phone numbers extracted successfully!',
			'count' => count($phone_numbers),
			'phone_numbers' => $phone_numbers,
		]);
	}
}