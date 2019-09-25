<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/22/19
 * Time: 12:38 AM
 */
namespace app\index\validate;

class UserValidate extends BaseValidate {

    /**
     * 验证场景值  通过这个可以验证不同的场景
     * @var array
     */
    protected $scene = [
        'register' => ['username', 'secret'],
        'login'  =>  ['username','secret'],
        'edit' => ['username'],
        'detail' => ['id'],
    ];

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'username' => ['require', 'length' => '4,25', 'regex' => '^[_\w\d]+$'],
        'secret' => ['require', 'regex' => '^(?![a-zA-Z]+$)(?![0-9]+$).{6,}$'],


    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '用户id不能为空',
        'id.number' => '用户id格式不正确',
        'username.require' => '用户名不能为空',
        'username.length' => '用户名长度范围为4~25',
        'username.regex' => '用户名只能为字母、数字或_',
        'secret.require' => '密码不能为空',
        'secret.regex' => '密码长度至少6位，并且不能是纯字符或纯数字',
    ];

    /**
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [
        'id.require' => 10001,
        'id.number' => 10003,
        'username.require' => 10001,
        'username.length' => 10003,
        'username.regex' => 10003,
        'secret.require' => 10001,
        'secret.regex' => 10003,
    ];


}