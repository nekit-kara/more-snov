<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 1:03
 */

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "category".
 *
 * @property integer $category_id
 * @property string $image
 * @property integer $parent_id
 * @property boolean $top
 * @property integer $column
 * @property integer $sort_order
 * @property boolean $status
 * @property integer $date_added
 * @property integer $date_modified
 */
class Category extends Model
{
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    protected $table = 'category';
    protected $primaryKey = 'category_id';

    public function description()
    {
        return $this->hasOne(get_class(new CategoryDescription()), 'category_id', 'category_id');
    }

    public function path()
    {
        return $this->hasMany(get_class(new CategoryPath()), 'category_id', 'category_id');
    }
}