<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/23/19
 * Time: 11:43 AM
 */


namespace app\http\middleware;

use app\http\exception\MyException;

/**
 * 应用全局中间件，http 头必要参数检测
 * Class HeaderCheckMiddleware
 * @package app\http\middleware
 */
class HeaderCheckMiddleware
{
    public function handle($request, \Closure $next)
    {
        app('api_client')->initialize();
        return $next($request);
    }
}