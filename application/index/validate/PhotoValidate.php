<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:53 PM
 */

namespace app\index\validate;


class PhotoValidate extends BaseValidate
{

    /**
     * 验证场景值  通过这个可以验证不同的场景
     * @var array
     */
    protected $scene = [
        'add' => ['album_id', 'urls'],
        'delete' => ['id'],
        'transfer' => ['id', 'album_id']
    ];

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'album_id' => ['require', 'number'],
        'urls' => ['require', 'array'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '图片id不能为空',
        'id.number' => '图片id格式不正确',
        'album_id.require' => '相册id不能为空',
        'album_id.number' => '相册id格式不正确',
        'urls.require' => '图片参数不能为空',
        'urls.array' => '图片参数格式不正确',
    ];

    /**
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [
        'id.require' => 10001,
        'id.number' => 10003,
        'album_id.require' => 10001,
        'album_id.number' => 10003,
        'urls.require' => 10001,
        'urls.array' => 10003,
    ];

}