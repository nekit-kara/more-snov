<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 0:24
 */

namespace App\Services;

use App\Repositories\CategoryDescriptionRepository;

class EvateksCategoryService
{
    private $categoryDescriptionRepository;

    public function __construct()
    {
        $this->categoryDescriptionRepository = new CategoryDescriptionRepository();
    }

    public function processCategoriesRawString($categoriesRawString)
    {
        $categories = explode('/', $categoriesRawString);
        $categories[0] = 'Халаты';

        if (is_array($categories)) {

            $parentId = 0;
            foreach ($categories as $category) {
                $category = $this->categoryDescriptionRepository->getCategoryDescriptionByName($category);
                if ($category) {
                    $parentId = $category->category_id;
                } else {

                }
            }
        }
    }
}