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
use App\Repositories\ManufacturerRepository;
use App\Repositories\ProductRepository;
use App\Validators\CategoryValidator;
use Curl\Curl;

class EvateksProductService
{
    public $productRepository;
    public $categoryRepository;
    public $manufacturerRepository;
    public $categoryValidator;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->categoryValidator = new CategoryValidator();
        $this->manufacturerRepository = new ManufacturerRepository();
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
            $categoryId = $categoryService->processCategoriesRawString($productData[FieldHelper::CATEGORY]);
            $productNameAndSKU = $this->getProductNameAndSKU($productData[FieldHelper::PRODUCT_NAME]);
            $photos = $this->processPhotoString($productData[FieldHelper::PHOTOS]);
            $manufacturerId = $this->getManufacturerIdByName($productData[FieldHelper::MANUFACTURER]);

            var_dump($photos);

            $product = new Product();
            $product->external_id = $productData[FieldHelper::PRODUCT_ID];
            /**@TODO переделать это поле */
            $product->duver_cover = 0;
            $product->model = $productNameAndSKU['sku'];
            $product->sku = $productNameAndSKU['sku'];
            $product->quantity = 999;
            $product->stock_status_id = 7;
            $product->image = $photos[0];
        }

        return null;
    }

    public function updateProduct(Product $product, array $productData)
    {

    }

    public function getProductNameAndSKU($string)
    {
        preg_match_all('/([^\)]+)\((.*)\)/m', $string, $matches, PREG_SET_ORDER, 0);

        $data = [
            'sku' => $matches[0][2],
            'name' => trim($matches[0][1])
        ];

        return $data;
    }

    public function processPhotoString($photoString)
    {
        $photoArray = explode(',', $photoString);

        $downloadedPhoto = [];

        $curl = new Curl();

        foreach ($photoArray as $photoUrl) {
            $newAbsImagePath = DIR_IMAGE . 'catalog/product/' . basename($photoUrl);
            $newImagePath = 'catalog/product/' . basename($photoUrl);
            $curl->download($photoUrl, $newAbsImagePath);
            $downloadedPhoto[] = $newImagePath;
        }

        $curl->close();

        return $downloadedPhoto;
    }

    public function getManufacturerIdByName($name)
    {
        $manufacturer = $this->manufacturerRepository->getManufacturerIdByName($name);
        if ($manufacturer) {
            return $manufacturer->manufacturer_id;
        }
        return $this->manufacturerRepository->createManufacturer($name);
    }
}