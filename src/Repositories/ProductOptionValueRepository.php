<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 13.11.18
 * Time: 9:34
 */

namespace App\Repositories;

use App\Models\Product\ProductOptionValue;

class ProductOptionValueRepository
{
    public function getProductOptionValue($data)
    {
        return ProductOptionValue::where([
            'product_option_id' => $data['product_option_id'],
            'product_id' => $data['product_id'],
            'option_id' => $data['option_id'],
            'option_value_id' => $data['option_value_id'],
        ])->first();
    }

    public function saveProductOptionValue($data)
    {
        $productOptionValue = new ProductOptionValue();
        $productOptionValue->product_option_id = $data['product_option_id'];
        $productOptionValue->product_id = $data['product_id'];
        $productOptionValue->option_id = $data['option_id'];
        $productOptionValue->option_value_id = $data['option_value_id'];
        $productOptionValue->quantity = 0;
        $productOptionValue->subtract = 0;
        $productOptionValue->price = 0;
        $productOptionValue->price_prefix = '+';
        $productOptionValue->points = 0;
        $productOptionValue->points_prefix = '+';
        $productOptionValue->weight = 0;
        $productOptionValue->weight_prefix = '+';

        if ($productOptionValue->save()) {
            return $productOptionValue->product_option_value_id;
        }
        return null;
    }
}