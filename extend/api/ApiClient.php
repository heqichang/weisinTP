<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/21
 * Time: 15:32
 */
namespace api;

use app\http\exception\MyException;
use think\Container;

/**
 * api客户端相关参数判断
 * Class ApiClient
 * @package api
 */
class ApiClient
{
    /**
     * 获取request对象
     * @var null
     */
    private $_request = null;

    private $_client = [
        // [必填]api版本，例如：v1
        'api_version' => '',

        // [选填]用户登录凭证
        'token' => '',

    ];

    public function __construct()
    {
        $this->_request = Container::get('request');
    }

    // 获取 $this->_client
    public function getClient($key = null)
    {
        if (empty($key)) {
            return $this->_client;
        }

        $this->_if_exist_client_key($key);
        return $this->_client[$key];
    }

    // 设置 $this->_client
    public function setClient($key, $value)
    {
        $this->_if_exist_client_key($key);
        $this->_client[$key] = $value;
    }

    // 判断$this->_client 的 key 是否存在
    private function _if_exist_client_key($key)
    {
        if (!isset($this->_client[$key])) {
            throw new MyException("API_CLIENT_ITEM_NOT_EXIST" . $key, 1);
        }
    }

    /**
     * 初始化参数
     */
    public function initialize()
    {
        $headers = $this->_request->header();

        $header_prefix = strtolower(config('api.header_prefix'));
        // 检查方法
        $headers_check_map = array(
            'api_version' => '_check_api_version',
        );
        // 必填
        $headers_must = array(
            'api_version' => true, // 必填
        );

        foreach ($headers as $key => $value) {
            $key = strtolower($key);
            if (strpos($key, $header_prefix) !== 0) {
                continue;
            }
            $key = str_replace('-', '_', substr($key, strlen($header_prefix)));
            $this->_if_exist_client_key($key);
            $this->_client[$key] = $value;
        }

        // 检查
        foreach ($this->_client as $key => $value) {
            if (isset($headers_check_map[$key])) {
                $must = false;
                // 必填
                if ($headers_must[$key] === true) {
                    $must = true;
                } // 选填，但不为空
                elseif ($headers_must[$key] === false && !empty($value)) {
                    $must = true;
                } // 指定平台 必填
                elseif (is_array($headers_must[$key]) && in_array($this->_client['platform'], $headers_must[$key])) {
                    $must = true;
                }

                if ($must) {
                    $method = $headers_check_map[$key];
                    $this->$method($value);
                }
            }
        }
    }

    // 格式：v1，最多两位数字v99
    private function _check_api_version($value)
    {
        if (!preg_match("#^v\\d{1,2}$#", $value)) {
            throw new MyException("API_CLIENT_API_VERSION_ERROR", 1);
        }
    }



}