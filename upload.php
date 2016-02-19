<?php

require 'vendor/autoload.php';
require 'config.php';

use Aws\S3\S3Client;

$opt = getopt("f:s:v:");
if(!isset($opt['f']) || !isset($opt['s']) || !isset($opt['v']) || intval($opt['v']) <= 0){
	echo "ERROR running the command. Please check the command parameter: ".PHP_EOL;
	echo "-f Full file path. Example: /home/user/file.pdf".PHP_EOL;
	echo "-v Validity of the S3 URL in months. Accepted value is integer more than 0".PHP_EOL;
	echo "-s Subfolder to store the file. Example: subfolder/another_sub_folder".PHP_EOL;
	echo PHP_EOL;
	return;
}


$bucket = AWS_S3_ROOT_BUCKET;
$urlValidity = '+'.intval($opt['v']).' months';
$folder = $opt['s'];
$filePath = $opt['f'];

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
echo "Successfully upload the file. The public URL valid in ".$opt['v']." months is: ".PHP_EOL;
echo $signedUrl. PHP_EOL;
echo PHP_EOL;
