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


    /**
     * 关联文章
     * @return \think\model\relation\HasMany
     */
    public function articles() {
        return $this->hasMany('ArticleModel', 'category_id', 'id');
    }

    /**
     * 查找用户下的目录列表
     * @param $userId
     * @return array|\PDOStatement|string|\think\Collection
     */
    public static function getByUserId($userId) {
        return self::field(['id, name'])
            ->where('user_id', $userId)
            ->select();
    }

}