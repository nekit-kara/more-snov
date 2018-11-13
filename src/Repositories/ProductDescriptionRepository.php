<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 13.11.18
 * Time: 9:07
 */

namespace App\Repositories;

use App\Models\Product\ProductDescription;

class ProductDescriptionRepository
{
    public function getProductDescription($productId, $productName)
    {
        return ProductDescription::where([
            'product_id' => $productId,
            'name' => $productName
        ])->first();
    }

    public function saveProductDescription($productId, $productName, $productNameWithSku, $description)
    {
        $productDescription = new ProductDescription();
        $productDescription->product_id = $productId;
        $productDescription->description = $description;
        $productDescription->language_id = 1;
        $productDescription->name = $productName;
        $productDescription->meta_title = $productNameWithSku;
        $productDescription->meta_description = $productNameWithSku;
        $productDescription->meta_h1 = $productNameWithSku;

        if ($productDescription->save()) {
            return true;
        }
        return null;
    }
}