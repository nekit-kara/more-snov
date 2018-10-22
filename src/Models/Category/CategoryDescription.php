<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 1:05
 */

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "category_description".
 *
 * @property integer $category_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $meta_h1
 */
class CategoryDescription extends Model
{
    protected $table = 'category_description';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}