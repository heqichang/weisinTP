<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:53 PM
 */

namespace app\index\service;


use app\http\exception\MyException;
use app\index\model\AlbumModel;
use app\index\model\PhotoModel;
use app\index\validate\PhotoValidate;

class PhotoService
{
    /**
     * 增加
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function add($post) {

        $validate = new PhotoValidate();
        $validate->run($post, '', 'add');

        // 测试相册是否在
        $album = AlbumModel::get($post['album_id']);

        $userId = app('api_user')->getUser('id');

        // 测试相册拥有者
        if ($userId != $album['user_id']) {
            throw new MyException('无权操作', 10023);
        }

        if (null === $album) {
            throw new MyException('该相册id不对', 10021);
        }

        PhotoModel::addPhotos($userId, $post['album_id'], $post['urls']);
    }


    /**
     * 编辑
     * @param $post
     * @throws \app\http\exception\FromValidException
     */
    public function transfer($post) {

        $validate = new PhotoValidate();
        $validate->run($post, '', 'transfer');

        $album = AlbumModel::get($post['album_id']);

        // 非作者不可用此操作
        $apiUserId = app('api_user')->getUser('id');

        if ($apiUserId != $album['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        if (null === $album) {
            throw new MyException('不存在该相册', 100011);
        }

        PhotoModel::update(['id' => $post['id'], 'album_id' => $post['album_id']]);
    }

    /**
     * 删除
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function delete($post) {

        $validate = new PhotoValidate();
        $validate->run($post, '', 'delete');

        $photo = PhotoModel::get($post['id']);

        // 非作者或管理员不可用此操作
        $apiUserId = app('api_user')->getUser('id');
        if ($apiUserId != $photo['user_id']) {
            throw new MyException('没有权限执行此操作', 10011);
        }

        $photo->delete();
    }


    /**
     * 图片列表
     * @param $get
     * @return \think\Paginator
     * @throws \app\http\exception\FromValidException
     */
    public function getList($get) {

        $validate = new PhotoValidate();
        $validate->run($get, '', 'list');

        $page = 1;
        if (isset($get['page'])) {
            $page = $get['page'];
        }

        return PhotoModel::getList($get['album_id'], $page);
    }



}