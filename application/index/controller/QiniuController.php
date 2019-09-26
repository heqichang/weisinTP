<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 4:49 PM
 */

namespace app\index\controller;


use app\index\service\QiniuService;

class QiniuController extends BaseController
{

    public function auth() {
        $service = new QiniuService();
        $result = $service->getToken();
        return $this->response_ok($result);
    }

}