<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:17
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_to_category".
 *
 * @property integer $product_id
 * @property integer $category_id
 * @property boolean $main_category
 */
class ProductToCategory extends Model
{
    protected $table = 'product_to_category';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}