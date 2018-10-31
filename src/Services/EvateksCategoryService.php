<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 0:24
 */

namespace App\Services;

use App\Repositories\CategoryDescriptionRepository;
use App\Repositories\CategoryRepository;

class EvateksCategoryService
{
    private $categoryDescriptionRepository;
    private $categoryRepository;

    public function __construct()
    {
        $this->categoryDescriptionRepository = new CategoryDescriptionRepository();
        $this->categoryRepository = new CategoryRepository();
    }

    public function processCategoriesRawString($categoriesRawString)
    {
        $categories = explode('/', $categoriesRawString);
        $categories[0] = 'Халаты';

        if (is_array($categories)) {

            $categoryId = 0;
            $level = 0;
            foreach ($categories as $categoryName) {
                $category = $this->categoryDescriptionRepository->getCategoryDescriptionByName($categoryName);
                if ($category) {
                    $categoryId = $category->category_id;
                } else {
                    $categoryId = $this->categoryRepository->createCategory($categoryId, $categoryName, $level);
                }

                $level++;
            }

            if ($categoryId !== 0 && $categoryId !== null) {
                return $categoryId;
            }
        }
        return null;
    }
}