<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/21
 * Time: 11:10
 */
namespace api;
use app\http\exception\MyException;

/**
 * 客户端
 * Class ApiUser
 * @package api
 */
class ApiUser{
    /**
     * 用户的信息
     * @var array
     */
    private $_user = array();

    // 获取 $this->_user
    public function getUser($key = null) {
        if (empty($key)) {
            return $this->_user;
        }

        $this->_if_exist_user_key($key);
        return $this->_user[$key];
    }

    /**
     * 设置 $this->_user
     *
     * 参数
     * 设置单个键值(可以覆盖同名键值)：key, value
     * 设置多个键值(可以覆盖数组)：array
     */
    public function setUser() {
        $num_args = func_num_args();
        if ($num_args == 1) {
            $kv_array = func_get_arg(0);
            if (is_array($kv_array)) {
                $this->_user = $kv_array;
            }
        } elseif($num_args == 2) {
            $key = func_get_arg(0);
            $value = func_get_arg(1);
            $this->_user[$key] = $value;
        }
    }

    // 判断$this->_user 的 key 是否存在
    private function _if_exist_user_key($key) {
        if ( ! isset($this->_user[$key])) {
            throw new MyException("用户信息不存在", 90002);
        }
    }
}