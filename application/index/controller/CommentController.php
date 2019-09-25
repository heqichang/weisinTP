<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 9:20 AM
 */

namespace app\index\controller;


use app\index\service\CommentService;

class CommentController extends BaseController
{
    /**
     * 发布
     * @return \think\response\Json
     */
    public function add() {

        $post = $this->request->post();
        $service = new CommentService();
        $service->add($post);

        return $this->response_ok();
    }

    /**
     * 删除
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function delete() {

        $post = $this->request->post();
        $service = new CommentService();
        $service->delete($post);

        return $this->response_ok();
    }

    /**
     * 文章列表
     * @return \think\response\Json
     */
    public function getList() {

        $get = $this->request->get();

        $service = new CommentService();

        $result = $service->getList($get);

        return $this->response_ok($result);
    }


}