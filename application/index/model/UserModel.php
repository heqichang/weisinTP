<?php

namespace app\index\model;



class UserModel extends BaseModel
{
    //
    protected $table = 'user';
    protected $pk = 'id';


    /**
     * @param $name 用户名
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
