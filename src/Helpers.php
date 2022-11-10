<?php

use Slim\Http\UploadedFile;

/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploadedFile file uploaded file to move
 *
 * @return string filename of moved file
 * @throws Exception
 */
function moveUploadedFile(string $directory, UploadedFile $uploadedFile): string {

	if (!file_exists($directory)) {
		mkdir($directory, 0777, true);
	}

	$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
	$basename = bin2hex(random_bytes(12)); // see http://php.net/manual/en/function.random-bytes.php
	$filename = sprintf('%s.%0.8s', $basename, $extension);

	$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

	return $filename;
}

function randString($length = 16): string {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$string = '';

	for ($i = 0; $i < $length; $i++) {
		$string .= $characters[mt_rand(0, strlen($characters) - 1)];
	}

	return $string;
}
