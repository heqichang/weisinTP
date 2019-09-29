<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/27
 * Time: 20:30
 */
//redis的配置
$config = [
    // 服务器地址
    'host' => env('redis.host', '127.0.0.1'),
    // 缓存前缀
    'key_prefix' => env('redis.key_prefix', 'weisin'),
    // 缓存有效期 0表示永久缓存
    'expire' => env('redis.expire', 0),
    //redis密码
    'password' => env('redis.password', 0),

    //连接时间
    'timeout' => env('redis.timeout', 0),
    //连接端口
    'port' => env('redis.port', 6379),

    // 用到的redis数据库索引，一般情况下用一个数据库就行了，要跟运维人员确定用哪个库
    'dbs' => [
        'default' => env('REDIS.DEFAULT_DB', 0), // 默认
    ],
    // 默认数据库，即$config['dbs']配置数组中的某个key
    'default_db' => 'default',

];

// 验证码表
$config['table']['captcha'] = [
    'timestamp',//pc登录验证码时间戳
];

return $config;