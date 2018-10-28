<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 0:22
 */

namespace App\Repositories;

use App\Models\Category\Category;

class CategoryRepository
{
    private $categoryDescriptionRepository;
    private $categoryPathRepository;

    public function __construct()
    {
        $this->categoryDescriptionRepository = new CategoryDescriptionRepository();
        $this->categoryPathRepository = new CategoryPathRepository();
    }

    public function createCategory($parentId, $categoryName, $level)
    {
        $category = new Category();

        $category->parent_id = $parentId;
        $category->status = true;

        if ($parentId == 0) {
            $category->top = true;
        } else {
            $category->top = false;
        }

        if ($category->save()) {

            $categoryDescriptionId = $this->categoryDescriptionRepository->createCategoryDescription(
                $category->category_id,
                $categoryName
            );

            $categoryPathId = $this->categoryPathRepository->createCategoryPath(
                $category->category_id,
                $parentId,
                $level
            );

            if ($categoryDescriptionId && $categoryPathId) {
                return $category->category_id;
            }
        }
        return null;
    }
}