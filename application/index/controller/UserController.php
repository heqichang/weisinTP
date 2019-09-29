<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/20/19
 * Time: 3:45 PM
 */

namespace app\index\controller;

use app\index\service\UserService;
use app\http\exception\MyException;
use redis\MyRedis;

/**
 * 用户控制器
 * Class UserController
 * @package app\index\controller
 */
class UserController extends BaseController {

    /**
     * 注册
     * @return \think\response\Json
     * @throws MyException
     */
    public function register() {

        $post = $this->request->post();

        $service = new UserService();
        $result = $service->registUser($post);

        if ($result) {
            return $this->response_ok();
        } else {
            throw new MyException('注册用户失败', 9);
        }
    }

    /**
     * 登录
     * @return \think\response\Json
     * @throws MyException
     */
    public function login() {


        $post = $this->request->post();

        $service = new UserService();
        $result = $service->login($post);

        return $this->response_ok($result);
    }

    /**
     * 编辑
     * @return \think\response\Json
     * @throws MyException
     */
    public function edit() {

        $post = $this->request->post();

        $service = new UserService();

        $service->edit($post);

        return $this->response_ok();
    }

    /**
     * 详情
     * @return \think\response\Json
     * @throws MyException
     */
    public function detail() {

        $get = $this->request->get();

        $service = new UserService();

        $result = $service->detail($get);

        return $this->response_ok($result);
    }

    // TODO: 待开发
    public function delete() {

    }

}