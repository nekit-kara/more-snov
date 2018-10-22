<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:11
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_image".
 *
 * @property integer $product_image_id
 * @property integer $product_id
 * @property string $image
 * @property integer $sort_order
 */
class ProductImage extends Model
{
    protected $table = 'product_image';
    protected $primaryKey = 'product_image_id';
    public $timestamps = false;
}