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
use App\Models\Product\ProductToCategory;
use App\Repositories\CategoryRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\OptionRepository;
use App\Repositories\ProductRepository;
use App\Validators\CategoryValidator;
use Curl\Curl;

class EvateksProductService
{
    public $productRepository;
    public $categoryRepository;
    public $manufacturerRepository;
    public $optionRepository;
    public $categoryValidator;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->manufacturerRepository = new ManufacturerRepository();
        $this->optionRepository = new OptionRepository();
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
            $arCategories = $categoryService->processCategoriesRawString($productData[FieldHelper::CATEGORY]);
            $productNameAndSKU = $this->getProductNameAndSKU($productData[FieldHelper::PRODUCT_NAME]);
            $photos = $this->processPhotoString($productData[FieldHelper::PHOTOS]);
            $manufacturerId = $this->getManufacturerIdByName($productData[FieldHelper::MANUFACTURER]);

            $product = new Product();
            $product->external_id = $productData[FieldHelper::PRODUCT_ID];
            /**@TODO переделать это поле */
            $product->duver_cover = 0;
            $product->model = $productNameAndSKU['sku'];
            $product->sku = $productNameAndSKU['sku'];
            $product->quantity = 999;
            $product->stock_status_id = 7;
            $product->image = $photos[0];
            $product->manufacturer_id = $manufacturerId;
            $product->shipping = true;
            $product->price = (int)$productData[FieldHelper::PRICE];
            $product->points = 0;
            $product->tax_class_id = 0;
            $product->date_available = time();
            $product->weight = 0;
            $product->length = 0;
            $product->height = 0;
            $product->length_class_id = 1;
            $product->subtract = true;
            $product->minimum = 1;
            $product->sort_order = 0;
            $product->status = true;
            $product->viewed = 0;

//            if ($product->save()) {
//                $this->saveRelationToCategory($product->product_id, $arCategories);
                $this->saveProductOptions($product->product_id, $productData[FieldHelper::SIZE]);
//            }
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

    public function saveRelationToCategory($productId, $categories)
    {
        foreach ($categories as $categoryId) {
            $productToCategory = new ProductToCategory();
            $productToCategory->product_id = $productId;
            $productToCategory->category_id = $categoryId;
            $productToCategory->main_category = 0;
            $productToCategory->save();
        }
    }

    public function saveProductOptions($productId, $options)
    {
        $sizes = explode(',', $options);

        foreach ($sizes as $size) {
            $optionValueDescription = $this->optionRepository->getOptionValueDescriptionByName(trim($size));

            if ($optionValueDescription) {
                $optionId = $optionValueDescription->option_id;

            } else {
                var_dump($size);
//                $optionId = $this->optionRepository->saveOption(trim($size));
            }
        }
    }
}