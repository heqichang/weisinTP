<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 2:53 PM
 */

namespace app\index\model;


class CategoryModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'category';


    public function articles() {
        return $this->hasMany('ArticleModel', 'category_id', 'id');
    }

}