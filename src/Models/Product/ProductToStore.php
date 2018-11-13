<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 13.11.18
 * Time: 9:49
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_to_store".
 *
 * @property integer $product_id
 * @property integer $store_id
 */
class ProductToStore extends Model
{
    protected $table = 'product_to_store';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}