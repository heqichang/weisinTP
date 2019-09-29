<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/29/19
 * Time: 4:45 PM
 */

namespace app\index\controller;


use captcha\Captcha;
use think\facade\Config;

class CaptchaController extends BaseController
{

    public function index($timestamp) {
        $capatch = new Captcha();
        return $capatch->create($timestamp);
    }

}