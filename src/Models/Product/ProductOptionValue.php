<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:14
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $table = 'product_option_value';
    protected $primaryKey = 'product_option_value_id';
    public $timestamps = false;
}