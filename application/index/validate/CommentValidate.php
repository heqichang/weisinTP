<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 9:20 AM
 */

namespace app\index\validate;


class CommentValidate extends BaseValidate
{
    /**
     * 验证场景值  通过这个可以验证不同的场景
     * @var array
     */
    protected $scene = [
        'add' => ['article_id', 'content'],
        'delete' => ['id'],
        'list' => ['article_id', 'page']
    ];

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'article_id' => ['require', 'number'],
        'content' => ['require', 'checkContent'],
        'page' => ['number']
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'id.require' => '留言id不能为空',
        'id.number' => '留言id格式不正确',
        'article_id.require' => '文章id不能为空',
        'article_id.number' => '文章id格式不正确',
        'content.require' => '留言不能为空',
        'content.checkContent' => '留言内容不能为空',
        'page.number' => '页码格式不正确',
    ];

    /**
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [
        'id.require' => 10001,
        'id.number' => 10003,
        'article_id.require' => 10001,
        'article_id.number' => 10003,
        'content.require' => 10001,
        'content.checkContent' => 10003,
        'page.number' => 10003,
    ];


    protected function checkContent($value) {
        $len = mb_strlen(trim($value));

        if (false === $len) {
            return false;
        }

        return $len > 0;
    }

}