<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/2/26
 * Time: 15:05
 */
namespace app\http\exception;

use Exception;

/**
 * 自定义的异常基类  新增其他异常类的时候请继承这个类
 * Class MyException
 * @package app\http\exception
 */
class MyException extends Exception
{

    // 消息类型 array|string
    private $_message_type = '';

    public function __construct($message = NULL, $code = 1, $previous = NULL)
    {
        if (is_array($message)) {
            $this->_message_type = 'array';
            $message = serialize($message);
        } else {
            $this->_message_type = 'string';
        }
        parent::__construct($message, $code, $previous);
    }

    // 获取消息，根据传入消息类型的不同，可能返回字符串或者数组
    public function getMyMessage()
    {
        $message = $this->getMessage();
        if ($this->_message_type == 'array') {
            return unserialize($message);
        } else {
            return $message;
        }
    }

}