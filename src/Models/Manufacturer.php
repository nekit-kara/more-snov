<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 31.10.18
 * Time: 10:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "manufacturer".
 *
 * @property integer $manufacturer_id
 * @property string $name
 * @property string $image
 * @property integer $sort_order
 */
class Manufacturer extends Model
{
    protected $table = 'manufacturer';
    protected $primaryKey = 'manufacturer_id';
    public $timestamps = false;
}