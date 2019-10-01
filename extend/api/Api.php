<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/20
 * Time: 10:55
 */
namespace api;

use app\http\exception\MyException;
use Firebase\JWT\JWT;
use think\Container;

class Api
{
    /**
     * 解析后的token数组
     * @var array
     */
    private $_decode_token = [];

    /**
     * 获取request对象
     * @var null
     */
    private $_request = null;

    public function __construct()
    {
        $this->_request = Container::get('request');
    }


    /**
     * 生成token
     * @param $uid  用户的唯一id
     * @param array $ext 拓展信息
     * @throws \Exception
     */
    public function createToken($uid, $ext = [])
    {
        try {
            return $this->tokenEncode($uid, $ext);
        } catch (\Exception $e) {
            throw new MyException('生成token错误', 90000);
        }
    }

    /**
     * 生成token
     * @param $uid  用户唯一id
     * @param array $ext 拓展信息
     * @param int $exp 有效时间  秒
     * @return string  返回token
     */
    public function tokenEncode($uid, $ext = [], $exp = 0)
    {
        $time = time();

        $jti = md5(
            $this->_request->server('REMOTE_ADDR') .
            $this->_request->server('REMOTE_PORT') .
            $this->_request->server('HTTP_USER_AGENT') .
            $this->_request->server('REQUEST_TIME') .
            microtime() .
            uniqid('', true) .
            $uid
        );


        $exp = $exp > 0 ? $exp : config('api.jwt.exp');

        $payload = array(
            'iat' => $time, // 签发时间
            'exp' => $time + $exp, // 有效时间
            'sub' => $uid, // 用户身份标识
            'jti' => $jti, // jwt的唯一身份标识
            'ext' => array(), // 自定义扩展信息
        );
        if (is_array($ext) && !empty($ext)) {
            $payload['ext'] = $ext; // 自定义扩展信息
        }

        return JWT::encode($payload, config('api.jwt.secret'), 'HS256');
    }

    /**
     * 初始化
     *
     * TODO：
     * token回收机制
     * 频率限制
     * 单客户端登录机制
     *
     * @param  string $token 加密后的token
     * @return bool
     */
    public function initializeToken($token)
    {
        try {
            $this->_decode_token = $this->tokenDecode($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 验证授权
     */
    public function authorization()
    {
        if (empty($this->_decode_token)) {
            // 过期、恶意篡改、无法解释 等情况都返回过期，客户端需要重新登录获取新token
            throw new MyException('token已经过期，请重新登录', 90001);
        }
    }

    /**
     * 解密token
     * @param $token
     * @return mixed
     */
    public function tokenDecode($token)
    {
        // JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($token, config('api.jwt.secret'), array('HS256'));
        return json_decode(json_encode($decoded), true);
    }

    /**
     * 获取 $this->_decode_token
     */
    public function getDecodeToken($key = null)
    {
        if (empty($key)) {
            return $this->_decode_token;
        }

        if (!isset($this->_decode_token[$key])) {
            throw new MyException("API_DECODE_TOKEN_ITEM_NOT_EXIST", 1);
        }

        return $this->_decode_token[$key];
    }
}