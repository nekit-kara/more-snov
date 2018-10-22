<?php

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';

$products = \App\Models\Product::all()->toArray();

echo '<pre>';
print_r($products);
echo '</pre>';