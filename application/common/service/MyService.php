<?php
namespace app\common\service;

/**
 * service 的基类
 * Class MyService
 * @package app\common\service
 */
class MyService
{
    /**
     * app对象  可以根据这个来获取相关的
     * @var mixed|null|\think\App
     */
    public $app = null;

    public function __construct()
    {
        $this->app = app();
    }

}