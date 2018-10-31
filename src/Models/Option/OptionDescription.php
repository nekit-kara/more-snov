<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 01.11.2018
 * Time: 0:12
 */

namespace App\Models\Option;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "option_description".
 *
 * @property integer $option_id
 * @property integer $language_id
 * @property string $name
 */
class OptionDescription extends Model
{
    protected $table = 'option_description';
    protected $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;
}