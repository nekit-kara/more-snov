<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 24.10.2018
 * Time: 0:05
 */

namespace App\Services;

class ProductService
{
    public $productData;

    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }

    public function saveProduct()
    {
        var_dump($this->productData);
    }
}