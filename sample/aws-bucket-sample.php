<?php

require_once __DIR__ . '/vendor/autoload.php';

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

$helper = $awsBucket->listFiles();

print_r($helper);