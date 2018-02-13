<?php

//$categories = file_get_contents('http://api.textiloptom.net/v5/Api/categories?api_key=cf6140b384320a7c90fb153770e89a76');

//file_put_contents('test/log.txt', print_r($categories, true));

//exit;
$products = file_get_contents('http://api.textiloptom.net/v5/Api/productsExt.json?api_key=cf6140b384320a7c90fb153770e89a76');

file_put_contents('test/log.txt', print_r($products, true));
//

//var_dump(DIR_SYSTEM);