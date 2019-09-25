<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:08 PM
 */

namespace app\index\controller;


use app\index\service\AlbumService;

/**
 * 相册控制器
 * Class AlbumController
 * @package app\index\controller
 */
class AlbumController extends BaseController
{
    /**
     * 添加
     * @return \think\response\Json
     */
    public function add() {
        $post = $this->request->post();
        $service = new AlbumService();
        $service->add($post);

        return $this->response_ok();
    }

    /**
     * 编辑
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function edit() {
        $post = $this->request->post();
        $service = new AlbumService();
        $service->edit($post);

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
     * 相册列表
     * @return \think\response\Json
     */
    public function getList() {

        $param = $this->request->param();

        $service = new AlbumService();
        $result = $service->getList($param);

        return $this->response_ok($result);
    }



}