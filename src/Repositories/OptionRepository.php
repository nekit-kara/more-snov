<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:22
 */

namespace App\Repositories;

use App\Models\Option\OptionValue;
use App\Models\Option\OptionValueDescription;

class OptionRepository
{
    public function getOptionValueDescriptionByName($name)
    {
        return OptionValueDescription::where('name', '=', $name)->first();
    }

    public function saveOption($name)
    {
        $sortOrder = OptionValue::where('option_id', '=', '14')->orderBy('sort_order', 'desc')->first()->sort_order;
        $sortOrder++;

        $optionValue = new OptionValue();
        $optionValue->option_id = 14;
        $optionValue->sort_order = $sortOrder;

        if ($optionValue->save()) {
            $optionValueDescription = new OptionValueDescription();
            $optionValueDescription->option_value_id = $optionValue->option_value_id;
            $optionValueDescription->language_id = 1;
            $optionValueDescription->option_id = 14;
            $optionValueDescription->name = trim($name);
            $optionValueDescription->save();

            return $optionValue->option_value_id;
        }
        return null;
    }
}