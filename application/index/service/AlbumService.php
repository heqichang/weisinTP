<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:19 PM
 */

namespace app\index\service;

use app\index\model\AlbumModel;
use app\index\validate\AlbumValidate;

/**
 * 相册业务处理
 * Class AlbumService
 * @package app\index\service
 */
class AlbumService
{
    /**
     * 增加
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function add($post) {

        $validate = new AlbumValidate();
        $validate->run($post, '', 'add');

        $userId = app('api_user')->getUser('id');

        $param = [
            'user_id' => $userId,
            'name' => $post['name'],
        ];

        AlbumModel::create($param);
    }


    /**
     * 编辑
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function edit($post) {

        $validate = new AlbumValidate();
        $validate->run($post, '', 'edit');

        $album = AlbumModel::get($post['id']);

        if (null === $album) {
            throw new MyException('不存在该相册', 100011);
        }

        // 非作者或管理员不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $album['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $album['name'] = $post['name'];

        $album->save();
    }

    /**
     * 删除
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function delete($post) {

        $validate = new AlbumValidate();
        $validate->run($post, '', 'delete');

        $album = AlbumModel::get($post['id'], 'photos');

        if (null === $album) {
            throw new MyException('不存在该文章', 100011);
        }

        // 非作者或管理员不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $album['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $album->together('photos')->delete();
    }


    /**
     * 相册列表
     * @param $param
     * @return \think\Paginator
     */
    public function getList($param) {

        $result = AlbumModel::getList($param);
        return $result;
    }

}