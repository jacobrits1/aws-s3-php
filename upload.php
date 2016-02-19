<?php

require 'vendor/autoload.php';
require 'config.php';

use Aws\S3\S3Client;

$bucket = AWS_S3_ROOT_BUCKET;
$urlValidity = '+12 months';
$folder = "example";
$filePath = "/Users/madeadi/Downloads/ipaymy-privacy-policy.pdf";

// create AWS S3 client
$client = S3Client::factory([
	'key' => AWS_ACCESS_KEY_ID,
	'secret' => AWS_SECRET_ACCESS_KEY,
]);

// concat random value in the file name to avoid brute force attack
// so hacker cannot guess the file name
$arr = explode('.',basename($filePath));
$fileName = $arr[0];
$fileExt = isset($arr[1]) ? $arr[1] : '';
$newFileName = $fileName."_".uniqid().".$fileExt";

$key = $folder.'/'.$newFileName;

$result = $client->putObject([
	'Bucket' => $bucket,
	'Key' => $key,
	'SourceFile' => $filePath,
	'Metadata' => [],
]);

$client->waitUntil('ObjectExists', [
	'Bucket' => $bucket,
	'Key' => $key,
]);

// get presigned URL. The file will not be accessible after $validity months
$signedUrl = $client->getObjectUrl($bucket, $key, $urlValidity);

// display the signed URL
d($signedUrl);
