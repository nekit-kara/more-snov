<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 26.10.2018
 * Time: 1:23
 */

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "category_path".
 *
 * @property integer $category_id
 * @property integer $path_id
 * @property integer $level
 */
class CategoryPath extends Model
{
    protected $table = 'category_path';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(get_class(new Category()), 'category_id', 'category_id');
    }
}