<?php

header('Content-Type: text/html; charset=UTF-8');

ini_set('display_errors', 'true');

require 'vendor/autoload.php';
require 'config.php';
require 'bootstrap.php';

$test = \App\Models\Category\Category::find(229);



echo '<pre>';
foreach ($test->path->toArray() as $path) {
    print_r($path);
}
print_r($test->description->name);
echo '</pre>';