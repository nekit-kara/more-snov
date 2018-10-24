<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 24.10.2018
 * Time: 23:02
 */

namespace App\Repositories;

use App\Models\Product\Product;

class ProductRepository
{
    public function getProduct($externalId)
    {
        return Product::where('external_id', '=', $externalId)->first();
    }
}