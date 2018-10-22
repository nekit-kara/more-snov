<?php

header('Content-Type: text/html; charset=UTF-8');

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';

$products = \App\Models\Product\Product::find(1470)->attribute->toArray();

echo '<pre>';
print_r($products);
echo '</pre>';