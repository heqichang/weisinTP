<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:06 AM
 */

namespace app\index\controller;

use app\index\service\ArticleService;

/**
 * 文章控制器
 * Class ArticleController
 * @package app\index\controller
 */
class ArticleController extends BaseController
{

    /**
     * 发布
     * @return \think\response\Json
     */
    public function post() {

        $post = $this->request->post();
        $service = new ArticleService();
        $service->post($post);

        return $this->response_ok();
    }

    /**
     * 编辑
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function edit() {
        $post = $this->request->post();
        $service = new ArticleService();
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
        $service = new ArticleService();
        $service->delete($post);

        return $this->response_ok();
    }

    /**
     * 文章列表
     * @return \think\response\Json
     */
    public function getList() {

        $get = $this->request->get();
        $service = new ArticleService();
        $result = $service->getList($get);

        return $this->response_ok($result);
    }

    /**
     * 文章详情
     * @return \think\response\Json
     * @throws \app\http\exception\MyException
     */
    public function detail() {
        $get = $this->request->get();

        $service = new ArticleService();
        $result = $service->getDetail($get);

        return $this->response_ok($result);
    }


}