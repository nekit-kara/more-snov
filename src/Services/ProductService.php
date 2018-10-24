<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 24.10.2018
 * Time: 0:05
 */

namespace App\Services;

use App\Helpers\FieldHelper;
use App\Models\Product\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    public $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function processProductDataRow(array $productData)
    {
        $product = $this->repository->getProduct($productData[FieldHelper::PRODUCT_ID]);

        if (!$product) {
            $this->saveProduct($productData);
        } else {
            $this->updateProduct($product, $productData);
        }
    }

    public function saveProduct(array $productData)
    {

    }

    public function updateProduct(Product $product, $productData)
    {

    }
}