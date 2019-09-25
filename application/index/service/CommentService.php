<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 9:20 AM
 */

namespace app\index\service;


use app\index\model\CommentModel;
use app\index\validate\CommentValidate;

/**
 * 留言业务处理
 * Class CommentService
 * @package app\index\service
 */
class CommentService
{
    /**
     * 发布留言
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function add($post) {

        $validate = new CommentValidate();
        $validate->run($post, '', 'add');

        $userId = app('api_user')->getUser('id');

        $param = [
            'user_id' => $userId,
            'article_id' => $post['article_id'],
            'content' => $post['content'],
        ];

        CommentModel::create($param);
    }

    /**
     * 删除
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function delete($post) {

        $validate = new CommentValidate();
        $validate->run($post, '', 'delete');

        $comment = CommentModel::get($post['id']);

        if (null === $comment) {
            throw new MyException('不存在该留言', 100011);
        }

        // 非作者不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $comment['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $comment->delete();
    }

    /**
     * 留言列表
     * @param $get
     * @return \think\Paginator
     */
    public function getList($get) {
        $validate = new CommentValidate();
        $validate->run($get, '', 'list');

        $page = 1;

        if (isset($get['page'])) {
            $page = $get['page'];
        }

        return CommentModel::getList($get['article_id'], $page);
    }
}