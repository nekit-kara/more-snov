<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 22.10.2018
 * Time: 21:57
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    protected $table = 'product';
    protected $primaryKey = 'product_id';

}