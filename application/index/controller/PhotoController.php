<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:53 PM
 */

namespace app\index\controller;
use app\index\service\PhotoService;

/**
 * 图片管理控制器
 * Class PhotoController
 * @package app\index\controller
 */
class PhotoController extends BaseController
{

    /**
     * 添加
     * @return \think\response\Json
     */
    public function add() {
        $post = $this->request->post();
        $service = new PhotoService();
        $service->add($post);

        return $this->response_ok();
    }

    /**
     * 相册转移
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function transfer() {
        $post = $this->request->post();
        $service = new PhotoService();
        $service->transfer($post);

        return $this->response_ok();
    }

    /**
     * 删除
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function delete() {
        $post = $this->request->post();
        $service = new AlbumService();
        $service->delete($post);

        return $this->response_ok();
    }

    /**
     * 目录列表
     * @return \think\response\Json
     */
    public function getList() {
        $service = new AlbumService();
        $result = $service->getList();

        return $this->response_ok($result);
    }

}