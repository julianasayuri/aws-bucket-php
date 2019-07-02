<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AwsBucket\AwsBucket;

$config = [
   'credentials' => [
       'key' => '',
       'secret' => '',
   ],
   'version' => 'latest',
   'region' => 'us-east-1',
   'bucket' => ''
];

$awsBucket = new AwsBucket($config);

$content = 'this is your file content';
$name = 'sample';
$extension = 'txt';

$putFile = $awsBucket->putFile($content, $name, $extension);
print_r($putFile);
echo PHP_EOL;

$listFiles = $awsBucket->listFiles();
print_r($listFiles);