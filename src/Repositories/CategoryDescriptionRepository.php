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
}