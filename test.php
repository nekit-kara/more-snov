<?php

header('Content-Type: text/html; charset=UTF-8');

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';
//
$service = new \App\Services\EvateksService();
$service->parse();

//$curl = new \Curl\Curl();
//$imagePath = 'https://evateks.ru/foto/55832b.jpg';
//$imageName = basename($imagePath);
//
//$curl->download($imagePath, DIR_IMAGE . 'catalog/product/' . $imageName);
//var_dump($curl->getDownloadCompleteCallback());
//$curl->close();


//echo '<pre>';
//print_r($products);
//echo '</pre>';