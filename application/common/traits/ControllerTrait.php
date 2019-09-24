<?php
namespace app\common\traits;

/**
 * 控制器公共的方法
 * Class ControllerTrait
 * @package app\common\traits
 */
trait ControllerTrait
{
    /**
     * 执行成功返回数据
     * @param $data
     * @return \think\response\Json
     */
    public function response_ok($data = null)
    {
        $json = [
            'code' => 0,
            'message' => 'success',
            'data' => $data
        ];

//        dump($json);
        return json($json);
    }

}