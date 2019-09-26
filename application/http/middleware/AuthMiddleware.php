<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/23/19
 * Time: 2:53 PM
 */


namespace app\http\middleware;

use app\http\exception\MyException;
use app\index\model\UserModel;

/**
 * 需要认证的接口中间件
 * Class AuthMiddleware
 * @package app\http\middleware
 */
class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        app('api_client')->initialize();

        $request->token = app('api_client')->getClient('token');

        //解析token
        $res = app('api')->initializeToken($request->token);

        if (!$res) {
            throw new MyException('token 转换失败', 90001);
        }

        app('api')->authorization();
        $userId = app('api')->getDecodeToken('sub');

        $user = UserModel::getByID($userId, ['id', 'username', 'avatar', 'status', 'delete_time'], true);

        if ($user->isEmpty()) {
            throw new MyException('没有找到该用户', 100011);
        }

        if ($user['delete_time'] != 0) {
            throw new MyException('该用户已被删除', 100021);
        }

        app('api_user')->setUser($user->toArray());

        return $next($request);
    }
}