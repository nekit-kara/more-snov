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
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Validators\CategoryValidator;

class EvateksProductService
{
    public $productRepository;
    public $categoryRepository;
    public $categoryValidator;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->categoryValidator = new CategoryValidator();
    }

    public function processProductDataRow(array $productData)
    {
        $product = $this->productRepository->getProduct($productData[FieldHelper::PRODUCT_ID]);

        if (!$product) {
            $this->saveProduct($productData);
        } else {
            $this->updateProduct($product, $productData);
        }
    }

    public function saveProduct(array $productData)
    {
        $isRobeCategories = $this->categoryValidator->isRobeCategories($productData[FieldHelper::CATEGORY]);
        $isForBusinessCategories = $this->categoryValidator->isForBusinessCategories($productData[FieldHelper::CATEGORY]);

        if ($isRobeCategories && !$isForBusinessCategories) {

            $categoryService = new EvateksCategoryService();
            $categoryService->processCategoriesRawString($productData[FieldHelper::CATEGORY]);

            $product = new Product();
            $product->external_id = $productData[FieldHelper::PRODUCT_ID];
        }

        return null;
    }

    public function updateProduct(Product $product, array $productData)
    {

    }
}