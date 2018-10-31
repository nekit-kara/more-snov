<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:16
 */

namespace App\Models\Option;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "option_value_description".
 *
 * @property integer $option_value_id
 * @property integer $language_id
 * @property integer $option_id
 * @property string $name
 */
class OptionValueDescription extends Model
{
    protected $table = 'option_value_description';
    protected $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;
}