<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:14
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_option_value".
 *
 * @property integer $product_option_value_id
 * @property integer $product_option_id
 * @property integer $product_id
 * @property integer $option_id
 * @property integer $option_value_id
 * @property integer $quantity
 * @property boolean $subtract
 * @property float $price
 * @property string $price_prefix
 * @property integer $points
 * @property string $points_prefix
 * @property float $weight
 * @property string $weight_prefix
 */
class ProductOptionValue extends Model
{
    protected $table = 'product_option_value';
    protected $primaryKey = 'product_option_value_id';
    public $timestamps = false;
}