<?php
namespace redis;

/**
 * 自定义redis操作类
 * Class MyRedis
 * @package redis
 */
class MyRedis
{
    /**
     * redis对象
     * @var null|object
     */
    private $_redis = null;

    /**
     * 配置
     * @var array|mixed
     */
    private $_config = [];

    /**
     * 数据库表
     * @var array
     */
    private $_table = [];

    public function __construct()
    {
        $this->_config = config('redis.');
        $this->_dbs = $this->_config['dbs'];
        $this->_table = $this->_config ['table'];

        $this->_redis = new \Redis();
        $this->_redis->connect($this->_config['host'], $this->_config['port'], $this->_config['timeout']);

        if (!empty($this->_config['password'])) {
            $this->_redis->auth($this->_config['password']);
        }

        // 选择数据库
        $this->__select($this->_config['default_db']);

        // 设置key前缀
        $this->_redis->setOption(\Redis::OPT_PREFIX, $this->_config['key_prefix'] . ':');
        // 设置序列化方式
        $this->_redis->setOption(\Redis::OPT_SERIALIZER, $this->serializer());


    }

    /**
     * 当调用一个不可访问方法（如未定义，或者不可见）时，__call() 会被调用。
     * 调用其他方法前要调用key方法组装key，否则出错
     *
     * @param  string $name 方法名
     * @param  array $args 参数数组
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        // 重写过的方法
        $overwrite_method = array('select');

        if (in_array($name, $overwrite_method)) {
            return call_user_func_array(array($this, '__' . $name), $args);
        }

        // 原生方法
        return call_user_func_array(array($this->_redis, $name), $args);
    }

    /**
     * 组装key
     *
     * 几种情况下返回的key，假设前缀是prefix
     *
     * $table = table，$id = 1，$field = name
     * 返回：prefix:table:1:name。一般用于string类型
     *
     * $table = table，$id = 1，$field = null
     * 返回：prefix:table:1。一般用于hash类型，字段放在hash表里
     *
     * $table = cache，$id = null，$field = some_cache
     * 返回：prefix:cache:some_cache。一般用于无序号id的单独一个key，例如缓存，计数器等
     *
     * @param  string $table 表
     * @param  mixed $id 标识key唯一性的id，可以是整数或字符串，例如id，姓名，手机号等
     * @param  string $field 字段
     *
     * @return string key
     */
    public function packKey($table, $id = null, $field = null)
    {
        if (!isset($this->_table[$table])) {
            exit('table not exists'); // 表不存在
        }

        if (!empty($field) && !in_array($field, $this->_table[$table])) {
            exit('field not exists'); // 表或字段不存在
        }

        return implode(':', array_filter(array($table, $id, $field)));
    }

    /**
     * 返回序列化工具
     *
     * @return int
     */
    protected function serializer()
    {
        return \Redis::SERIALIZER_PHP; // 默认的PHP序列化工具（即serialize方法）。
    }

    // -----------------------------------------------------------------
    // 重写的方法，一般直接调用原生方法，部分方法为了使用方便才考虑重写
    // -----------------------------------------------------------------

    /**
     * 选择数据库
     *
     * @param string $db_name 数据库别名
     *
     * @return bool
     */
    private function __select($db_name)
    {
        if (isset($this->_dbs[$db_name])) {
            return $this->_redis->select($this->_dbs[$db_name]);
        }
    }

}