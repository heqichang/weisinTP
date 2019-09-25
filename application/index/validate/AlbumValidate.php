<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:20 PM
 */

namespace app\index\validate;


class AlbumValidate extends BaseValidate
{
    /**
     * 验证场景值  通过这个可以验证不同的场景
     * @var array
     */
    protected $scene = [
        'add' => ['name'],
        'edit'  =>  ['id', 'name'],
        'delete' => ['id'],
    ];

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'name' => ['require'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '相册id不能为空',
        'id.number' => '相册id格式不正确',
        'name.require' => '相册名称不能为空',
    ];

    /**
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [
        'id.require' => 10001,
        'id.number' => 10003,
        'name.require' => 10001,
    ];

}