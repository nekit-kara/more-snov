<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:22
 */

namespace App\Repositories;

use App\Helpers\FieldHelper;
use App\Models\Option\OptionValue;
use App\Models\Option\OptionValueDescription;
use App\Models\Product\ProductOption;

class OptionRepository
{
    public function getOptionValueDescriptionByName($name)
    {
        return OptionValueDescription::where('name', '=', $name)->first();
    }

    public function saveOption($name)
    {
        $sortOrder = OptionValue::where('option_id', '=', FieldHelper::ID_SELECT_SIZES)
            ->orderBy('sort_order', 'desc')
            ->first()
            ->sort_order;

        $sortOrder++;

        $optionValue = new OptionValue();
        $optionValue->option_id = FieldHelper::ID_SELECT_SIZES;
        $optionValue->sort_order = $sortOrder;

        if ($optionValue->save()) {
            $optionValueDescription = new OptionValueDescription();
            $optionValueDescription->option_value_id = $optionValue->option_value_id;
            $optionValueDescription->language_id = 1;
            $optionValueDescription->option_id = FieldHelper::ID_SELECT_SIZES;
            $optionValueDescription->name = trim($name);
            $optionValueDescription->save();

            return $optionValue->option_value_id;
        }
        return null;
    }

    public function getProductOptionId($productId)
    {
        return ProductOption::where([
            'product_id' => $productId,
            'option_id' => FieldHelper::ID_SELECT_SIZES
        ])->first();
    }

    public function saveProductOption($productId)
    {
        $productOption = new ProductOption();
        $productOption->product_id = $productId;
        $productOption->option_id = FieldHelper::ID_SELECT_SIZES;
        $productOption->required = 1;
        if ($productOption->save()) {
            return $productOption->product_option_id;
        }
        return null;
    }
}