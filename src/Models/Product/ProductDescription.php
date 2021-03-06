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
 * This is the model class for table "product_description".
 *
 * @property integer $product_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 * @property string $tag
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $meta_h1
 */
class ProductDescription extends Model
{
    protected $table = 'product_description';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}