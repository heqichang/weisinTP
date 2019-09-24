<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:06 AM
 */

namespace app\index\controller;

use app\index\service\ArticleService;

class ArticleController extends BaseController
{

    public function post() {

        $post = $this->request->post();
        $service = new ArticleService();
        $service->post($post);

        return $this->response_ok();
    }

    public function edit() {

    }

    public function delete() {

    }

    public function getList() {

    }


}