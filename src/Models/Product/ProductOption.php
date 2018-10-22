<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:13
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_option".
 *
 * @property integer $product_option_id
 * @property integer $product_id
 * @property integer $option_id
 * @property string $value
 * @property boolean $required
 */
class ProductOption extends Model
{
    protected $table = 'product_option';
    protected $primaryKey = 'product_option_id';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(get_class(new Product()));
    }
}