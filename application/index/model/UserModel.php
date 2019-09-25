<?php

namespace app\index\model;



class UserModel extends BaseModel
{
    //
    protected $table = 'user';
    protected $pk = 'id';


//    public function articles()
//    {
//        return $this->hasMany('ArticleModel');
//    }

    /**
     * 拼接头像全路径地址
     * @param $value
     * @return string
     */
    public function getAvatarAttr($value) {
        return env('URL.IMAGE_BASE_URL') . $value;
    }

    /**
     * 根据用户名查找是否存在该用户
     * @param $username 用户名
     * @return bool
     */
    public function isNameExist($username)
    {
        $result = $this->where('username', $username)->findOrEmpty();
        return !$result->isEmpty();
    }

    /**
     * @param $username
     * @param string $fields 想查询的字段
     * @return array|\PDOStatement|string|\think\Model
     */
    public static function getByUsername($username, $fields = '*') {
        return static::field($fields)->where('username', $username)->findOrEmpty();
    }

    /**
     * @param $id
     * @param string $fields
     * @param bool $withTrashed
     * @return array|\PDOStatement|string|\think\Model
     */
    public static function getByID($id, $fields = '*', $withTrashed = false) {

        $db = static::field($fields);

        if ($withTrashed) {
            $db = static::withTrashed()->field($fields);
        }

        return $db->where('id', $id)->findOrEmpty();
    }


}
