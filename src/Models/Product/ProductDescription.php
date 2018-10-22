<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 0:02
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    protected $table = 'product_description';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}