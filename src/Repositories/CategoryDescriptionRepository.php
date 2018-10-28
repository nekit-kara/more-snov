<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 1:06
 */

namespace App\Repositories;

use App\Models\Category\CategoryDescription;

class CategoryDescriptionRepository
{
    public function getCategoryDescriptionByName($categoryName)
    {
        return CategoryDescription::where('name', '=', $categoryName)->first();
    }

    public function createCategoryDescription($categoryId, $name)
    {
        $categoryDescription = new CategoryDescription();
        $categoryDescription->category_id = $categoryId;
        $categoryDescription->language_id = 1;
        $categoryDescription->name = $name;

        if ($categoryDescription->save()) {
            return $categoryDescription->category_id;
        }
        return null;
    }
}