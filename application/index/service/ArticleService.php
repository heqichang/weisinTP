<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:10 AM
 */

namespace app\index\service;


use app\http\exception\MyException;
use app\index\model\ArticleModel;
use app\index\validate\ArticleValidate;

/**
 * 文章业务处理
 * Class ArticleService
 * @package app\index\service
 */
class ArticleService
{
    /**
     * 发布文章
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function post($post) {

        $validate = new ArticleValidate();
        $validate->run($post, '', 'post');

        $userId = app('api_user')->getUser('id');

        $param = [
            'user_id' => $userId,
            'title' => $post['title'],
        ];

        if (isset($post['content'])) {
            $param['content'] = $post['content'];
        }

        if (!empty($post['category_id'])) {
            $param['category_id'] = $post['category_id'];
        }

        ArticleModel::create($param);
    }


    /**
     * 编辑文章
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function edit($post) {

        $validate = new ArticleValidate();
        $validate->run($post, '', 'edit');

        $article = ArticleModel::get($post['id']);

        if (null === $article) {
            throw new MyException('不存在该文章', 10021);
        }

        // 非作者或管理员不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $article['user_id']) {
            throw new MyException('没有权限执行此操作', 10023);
        }

        $article['title'] = $post['title'];

        if (isset($post['content'])) {
            $article['content'] = $post['content'];
        } else {
            $article['content'] = null;
        }

        if (!empty($post['category_id'])) {
            $article['category_id'] = $post['category_id'];
        } else {
            $article['category_id'] = null;
        }

        $article->save();
    }

    /**
     * 删除文章
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function delete($post) {

        $validate = new ArticleValidate();
        $validate->run($post, '', 'delete');

        $article = ArticleModel::get($post['id'], 'comments');

        if (null === $article) {
            throw new MyException('不存在该文章', 10021);
        }

        // 非作者或管理员不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $article['user_id']) {
            throw new MyException('没有权限执行此操作', 10023);
        }

        $article->together('comments')->delete();
    }


    /**
     * 文章列表，可按日期，目录条件查找
     * @param $get
     * @return \think\Paginator
     */
    public function getList($get) {

        $validate = new ArticleValidate();
        $validate->run($get, '', 'list');

        $result = ArticleModel::getList($get);

        return $result;
    }

    /**
     * 文章详情
     * @param $get
     * @return null|static
     * @throws MyException
     */
    public function getDetail($get) {

        $validate = new ArticleValidate();
        $validate->run($get, '', 'detail');

        $model = ArticleModel::getDetail($get['id']);

        if (null === $model) {
            throw new MyException('没有找到该文章', 10021);
        }

        return $model;
    }


}