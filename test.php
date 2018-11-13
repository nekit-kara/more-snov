<?php

header('Content-Type: text/html; charset=UTF-8');

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';
//
$service = new \App\Services\EvateksService();
$service->parse();

//var_dump(123);