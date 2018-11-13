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
use App\Models\Product\ProductAttribute;
use App\Models\Product\ProductToCategory;
use App\Models\Product\ProductToLayout;
use App\Models\Product\ProductToStore;
use App\Repositories\CategoryRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\OptionRepository;
use App\Repositories\ProductDescriptionRepository;
use App\Repositories\ProductOptionValueRepository;
use App\Repositories\ProductRepository;
use App\Validators\CategoryValidator;
use Curl\Curl;

class EvateksProductService
{
    public $productRepository;
    public $categoryRepository;
    public $manufacturerRepository;
    public $optionRepository;
    public $productDescriptionRepository;
    public $productOptionValueRepository;
    public $categoryValidator;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->manufacturerRepository = new ManufacturerRepository();
        $this->optionRepository = new OptionRepository();
        $this->productDescriptionRepository = new ProductDescriptionRepository();
        $this->productOptionValueRepository = new ProductOptionValueRepository();
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

            if (!$productNameAndSKU['sku']) {
                return null;
            }

            $photos = $this->processPhotoString($productData[FieldHelper::PHOTOS]);
            $manufacturerId = $this->getManufacturerIdByName($productData[FieldHelper::MANUFACTURER]);

            $product = new Product();
            $product->external_id = $productData[FieldHelper::PRODUCT_ID];
            /**@TODO переделать это поле */
            $product->duvet_cover = 0;
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
            $product->date_available = date('Y-m-d', time());
            $product->weight = 0;
            $product->length = 0;
            $product->height = 0;
            $product->length_class_id = 1;
            $product->subtract = true;
            $product->minimum = 1;
            $product->sort_order = 0;
            $product->status = true;
            $product->viewed = 0;

            if ($product->save()) {
                $this->saveProductDescription(
                    $product->product_id,
                    $productNameAndSKU['name'],
                    $productData[FieldHelper::PRODUCT_NAME],
                    $productData[FieldHelper::DESCRIPTION]
                );
                $this->saveRelationToCategory($product->product_id, $arCategories);
                $optionsValueData = $this->saveProductOptions($product->product_id, $productData[FieldHelper::SIZE]);
                foreach ($optionsValueData as $optionsValueDatum) {
                    $this->saveProductOptionsValue($optionsValueDatum);
                }
                $this->saveProductToLayout($product->product_id);
                $this->saveProductToStore($product->product_id);
                $this->saveProductAttribute(
                    $product->product_id,
                    $productData[FieldHelper::COLOR],
                    FieldHelper::ID_COLOR_ATTRIBUTE
                );
                $this->saveProductAttribute(
                    $product->product_id,
                    $productData[FieldHelper::COMPOSITION],
                    FieldHelper::ID_COMPOSITION_ATTRIBUTE
                );

                var_dump($product->product_id);
            }
        }

        return null;
    }

    public function updateProduct(Product $product, array $productData)
    {
        return null;
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

        $optionsValueData = [];

        foreach ($sizes as $size) {
            $optionValueDescription = $this->optionRepository->getOptionValueDescriptionByName(trim($size));

            if ($optionValueDescription) {
                $optionValueId = $optionValueDescription->option_value_id;
            } else {
                $optionValueId = $this->optionRepository->saveOption(trim($size));
            }

            $productOption = $this->optionRepository->getProductOptionId($productId);

            if ($productOption) {
                $productOptionId = $productOption->product_option_id;
            } else {
                $productOptionId = $this->optionRepository->saveProductOption($productId);
            }

            $optionsValueData[] = [
                'product_option_id' => $productOptionId,
                'product_id' => $productId,
                'option_id' => FieldHelper::ID_SELECT_SIZES,
                'option_value_id' => $optionValueId
            ];
        }

        return $optionsValueData;
    }

    public function saveProductDescription($productId, $productName, $productNameWithSku, $description)
    {
        $productDescription = $this->productDescriptionRepository->getProductDescription($productId, $productName);

        if (!$productDescription) {
            $this->productDescriptionRepository->saveProductDescription(
                $productId,
                $productName,
                $productNameWithSku,
                $description
            );
        }
    }

    public function saveProductOptionsValue($data)
    {
        $productOptionValue = $this->productOptionValueRepository->getProductOptionValue($data);

        if (!$productOptionValue) {
            $this->productOptionValueRepository->saveProductOptionValue($data);
        }
    }

    public function saveProductToLayout($productId)
    {
        $productToLayout = ProductToLayout::where('product_id', '=', $productId)->first();

        if (!$productToLayout) {
            $newProductToLayout = new ProductToLayout();
            $newProductToLayout->product_id = $productId;
            $newProductToLayout->store_id = 0;
            $newProductToLayout->layout_id = 0;
            $newProductToLayout->save();
        }
    }

    public function saveProductToStore($productId)
    {
        $productToStore = ProductToStore::where('product_id', '=', $productId)->first();

        if (!$productToStore) {
            $newProductToStore = new ProductToStore();
            $newProductToStore->product_id = $productId;
            $newProductToStore->store_id = 0;
            $newProductToStore->save();
        }
    }

    public function saveProductAttribute($productId, $attributeValue, $attributeType)
    {
        $productAttribute = ProductAttribute::where([
            'product_id' => $productId,
            'text' => $attributeValue
        ])->first();

        if (!$productAttribute) {
            $newProductAttribute = new ProductAttribute();
            $newProductAttribute->product_id = $productId;
            $newProductAttribute->attribute_id = $attributeType;
            $newProductAttribute->language_id = 1;
            $newProductAttribute->text = $attributeValue;
            $newProductAttribute->save();
        }
    }
}