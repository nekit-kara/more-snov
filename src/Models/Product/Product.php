<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 22.10.2018
 * Time: 21:57
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product".
 *
 * @property integer $product_id
 * @property integer $external_id
 * @property string $model
 * @property string $sku
 * @property string $upc
 * @property string $ean
 * @property string $jan
 * @property string $isbn
 * @property string $mpn
 * @property string $location
 * @property integer $quantity
 * @property integer $stock_status_id
 * @property string $image
 * @property integer $manufacturer_id
 * @property boolean $shipping
 * @property float $price
 * @property integer $points
 * @property integer $tax_class_id
 * @property integer $date_available
 * @property float $weight
 * @property integer $weight_class_id
 * @property float $length
 * @property float $width
 * @property float $height
 * @property integer $length_class_id
 * @property boolean $subtract
 * @property integer $minimum
 * @property integer $sort_order
 * @property boolean $status
 * @property integer $viewed
 * @property integer $date_added
 * @property integer $date_modified
 */
class Product extends Model
{
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public static function latest($column = 'created_at'){
        return \Illuminate\Database\Query\Builder::latest($column);
    }

    public function attribute()
    {
        return $this->hasMany(get_class(new ProductAttribute()));
    }

    public function option()
    {
        return $this->hasMany(get_class(new ProductOption()));
    }
}