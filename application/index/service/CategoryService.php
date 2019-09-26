<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 2:59 PM
 */

namespace app\index\service;


use app\http\exception\MyException;
use app\index\model\CategoryModel;
use app\index\validate\CategoryValidate;

class CategoryService
{
    /**
     * 增加
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function add($post) {

        $validate = new CategoryValidate();
        $validate->run($post, '', 'add');

        $userId = app('api_user')->getUser('id');

        $param = [
            'user_id' => $userId,
            'name' => $post['name'],
        ];

        CategoryModel::create($param);
    }


    /**
     * 编辑
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function edit($post) {

        $validate = new CategoryValidate();
        $validate->run($post, '', 'edit');

        $category = CategoryModel::get($post['id']);

        if (null === $category) {
            throw new MyException('不存在该目录', 100011);
        }

        // 非作者不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $category['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $category['name'] = $post['name'];

        $category->save();
    }

    /**
     * 删除
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function delete($post) {

        $validate = new CategoryValidate();
        $validate->run($post, '', 'delete');

        $category = CategoryModel::get($post['id'], 'articles');

        if (null === $category) {
            throw new MyException('不存在该文章', 100011);
        }

        // 非作者不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $category['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $category->together('articles')->delete();
    }


    /**
     * 目录列表
     * @param $get
     * @return \think\Paginator
     */
    public function getList() {

        $result = CategoryModel::field('id, name')->select();

        return $result;
    }


}