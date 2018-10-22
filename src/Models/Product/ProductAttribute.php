<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:02
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $product_id
 * @property integer $attribute_id
 * @property integer $language_id
 * @property string $text
 */
class ProductAttribute extends Model
{
    protected $table = 'product_attribute';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(get_class(new Product()));
    }
}