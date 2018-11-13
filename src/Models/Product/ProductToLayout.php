<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 13.11.18
 * Time: 9:45
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_to_layout".
 *
 * @property integer $product_id
 * @property integer $store_id
 * @property integer $layout_id
 */
class ProductToLayout extends Model
{
    protected $table = 'product_to_layout';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}