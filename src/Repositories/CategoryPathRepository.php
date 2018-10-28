<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 27.10.2018
 * Time: 10:31
 */

namespace App\Repositories;

use App\Models\Category\CategoryPath;

class CategoryPathRepository
{
    public function createCategoryPath($categoryId, $parentId, $level)
    {
        $categoryPath = new CategoryPath();
        $categoryPath->category_id = $categoryId;

        if ($parentId == 0) {
            $categoryPath->path_id = $categoryId;
        } else {
            $categoryPath->path_id = $parentId;
        }

        $categoryPath->level = $level;

        if ($categoryPath->save()) {
            return $categoryPath->category_id;
        }
        return null;
    }
}