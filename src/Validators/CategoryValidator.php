<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 0:06
 */

namespace App\Validators;

class CategoryValidator
{
    public function isRobeCategories($rawCategoryString)
    {
        return preg_match('/(халат|Халат)/m', $rawCategoryString);
    }

    public function isForBusinessCategories($rawCategoryString)
    {
        return preg_match('/(бизнес|Бизнес)/m', $rawCategoryString);
    }
}