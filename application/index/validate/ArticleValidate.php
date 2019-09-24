<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:12 AM
 */

namespace app\index\validate;


class ArticleValidate extends BaseValidate
{

    /**
     * 验证场景值  通过这个可以验证不同的场景
     * @var array
     */
    protected $scene = [
        'post' => ['title', 'category_id'],
        'edit'  =>  ['title','category_id'],
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
//        'user_id' => ['require', 'number'],
        'title' => ['require'],
        'category_id' => ['number'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '文章id不能为空',
        'id.number' => '文章id格式不正确',
        'title.require' => '文章标题不能为空',
        'category_id.number' => '文章目录格式不正确',
    ];

    /**
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [
        'id.require' => 10001,
        'id.number' => 10003,
        'title.require' => 10001,
        'category_id.number' => 10003,

    ];



}