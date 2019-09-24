<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:10 AM
 */

namespace app\index\service;


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

        if (isset($post['category_id'])) {
            $param['category_id'] = $post['category_id'];
        }

        ArticleModel::create($param);
    }



}