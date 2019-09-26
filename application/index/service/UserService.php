<?php
/**
 * Created by PhpStorm.
 * User: weichen
 * Date: 2019/5/5
 * Time: 16:17
 */

namespace app\index\service;


use app\http\exception\MyException;
use app\index\model\UserModel;
use app\common\service\MyService;
use app\index\validate\UserValidate;

/**
 * 用户业务处理
 * Class UserService
 * @package app\index\service
 */
class UserService extends MyService
{
    /**
     * 用户注册
     * @param $post
     * @return bool
     */
    public function registUser($post)
    {
        $validate = new UserValidate();
        $validate->run($post, '', 'register');

        $user = new UserModel();
        $exists = $user->isNameExist($post['username']);
        if ($exists) {
            throw new MyException('用户名已存在', 10011);
        }

        $result = $user->save([
            'username' => $post['username'],
            'secret' => password_hash($post['secret'], PASSWORD_DEFAULT),
        ]);

        if (!$result) {
            throw new MyException('注册用户失败', 10012);
        }

        return $result;
    }

    /**
     * 用户登录
     * @param $post
     * @return array
     */
    public function login($post)
    {
        $validate = new UserValidate();
        $validate->run($post, '', 'login');

        $user = UserModel::getByUsername(
            $post['username'],
            ['id', 'secret']
        );

        if ($user->isEmpty()) {
            throw new MyException('用户不存在', 10013);
        }

        $verify = password_verify($post['secret'], $user['secret']);

        if (!$verify) {
            throw new MyException('密码不正确', 10014);
        }

        $token = app('api')->createToken($user['id'], [
            'login_time' => date('Y-m-d H:i:s'),
        ]);

        return [
            'id' => $user['id'],
            'token' => $token,
        ];
    }


    /**
     * 编辑用户
     * @param $post
     * @throws MyException
     * @throws \app\http\exception\FromValidException
     */
    public function edit($post)
    {
        $validate = new UserValidate();
        $validate->run($post, '', 'edit');

        $apiUserId = app('api_user')->getUser('id');
        $apiUsername = app('api_user')->getUser('username');

        $user = new UserModel();
        $exists = $user->isNameExist($post['username']);

        if ($exists && $apiUsername != $post['username']) {
            throw new MyException('用户名已存在', 10011);
        }

        $params = ['username' => $post['username']];

        if (isset($post['avatar'])) {
            $params['avatar'] = $post['avatar'];
        } else {
            $params['avatar'] = null;
        }

        UserModel::update($params, ['id' => $apiUserId]);
    }


    /**
     * 用户详情
     * @param $get
     * @return array|\PDOStatement|string|\think\Model
     * @throws MyException
     */
    public function detail($get) {

        $validate = new UserValidate();
        $validate->run($get, '', 'detail');

        $model = UserModel::getByID($get['id'], ['id', 'username', 'avatar', 'status']);

        if ($model->isEmpty()) {
            throw new MyException('不存在该用户', 100011);
        }

        return $model;
    }

    //TODO: 待开发
    public function delete($post) {


    }


}