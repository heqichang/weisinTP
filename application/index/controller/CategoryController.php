<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 2:57 PM
 */

namespace app\index\controller;


use app\index\service\CategoryService;

class CategoryController extends BaseController
{
    /**
     * 添加
     * @return \think\response\Json
     */
    public function add() {
        $post = $this->request->post();
        $service = new CategoryService();
        $result = $service->add($post);

        return $this->response_ok($result);
    }

    /**
     * 编辑
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function edit() {
        $post = $this->request->post();
        $service = new CategoryService();
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
        $service = new CategoryService();
        $service->delete($post);

        return $this->response_ok();
    }

    /**
     * 目录列表
     * @return \think\response\Json
     */
    public function getList() {
        $service = new CategoryService();
        $result = $service->getList();

        return $this->response_ok($result);
    }

}