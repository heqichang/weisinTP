<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 8:39 AM
 */

namespace app\http\middleware;

use app\http\exception\MyException;

/**
 * 路由中间件，顺序在 auth 之后，判断是否有管理员权限操作
 * Class AdminMiddleware
 * @package app\http\middleware
 */
class AdminMiddleware {

    public function handle($request, \Closure $next) {

        $isAdmin = app('api_user')->getUser('is_admin');

        if ($isAdmin == 0) {
            throw new MyException('没有权限做此操作', 20001);
        }

        return $next($request);
    }

}