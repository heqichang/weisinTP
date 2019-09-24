<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/20
 * Time: 10:33
 */


return [
    //jwt配置
    'jwt' => [
        //jwt的加密的key
        'secret' => env('api.jwt_secret'),
        //生效时间  1年
        'exp' => env('api.jwt_exp',31536000),
    ],
    //自定义的header前缀
    'header_prefix' => 'my-',
    //允许具体域名(用于正式生产环境)
    'access_control_allow_origin' => env('api.access_control_allow_origin','*'),
    //允许的http动作
    'access_control_allow_methods' => [
        'GET',
        'POST',
        'OPTIONS',
    ],
    //允许返回的头
    'access_control_allow_headers' => [
        'x-token',
        'x-uid',
        'x-token-check',
        'x-requested-with',
        'content-type',
        'Host',
        'my-api-version',
        //token
        'my-token',
    ]
];