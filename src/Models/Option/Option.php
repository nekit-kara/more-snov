<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:09
 */

namespace App\Models\Option;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "option".
 *
 * @property integer $option_id
 * @property string $type
 * @property integer $sort_order
 */
class Option extends Model
{
    protected $table = 'option';
    protected $primaryKey = 'option_id';
    public $timestamps = false;
}