<?php

use App\Models\Product\ProductOption;

header('Content-Type: text/html; charset=UTF-8');

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';

$test = ProductOption::where([
    'product_id' => 1480,
    'option_id' => '15',
])->first();



echo '<pre>';
print_r($test->product_option_id);
echo '</pre>';