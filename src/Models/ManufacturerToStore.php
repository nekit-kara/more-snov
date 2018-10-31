<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 31.10.18
 * Time: 10:02
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "manufacturer_to_store".
 *
 * @property integer $manufacturer_id
 * @property integer $store_id
 */
class ManufacturerToStore extends Model
{
    protected $table = 'manufacturer_to_store';
    protected $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;
}