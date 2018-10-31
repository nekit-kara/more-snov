<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:14
 */

namespace App\Models\Option;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "option_value".
 *
 * @property integer $option_value_id
 * @property integer $option_id
 * @property string $image
 * @property integer $sort_order
 */
class OptionValue extends Model
{
    protected $table = 'option_value';
    protected $primaryKey = 'option_value_id';
    public $timestamps = false;
}