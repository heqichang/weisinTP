<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 5:11 PM
 */

namespace app\index\service;

use Qiniu\Auth;

class QiniuService
{
    public function getToken() {

        $accessKey = env('QINIU.ACCESSKEY');
        $secretKey = env('QINIU.SECRETKEY');
        $bucket = env('QINIU.BUCKET');

        $auth = new Auth($accessKey,$secretKey);
        $expires = 3600;

        $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","name":"$(x:name)"}';
        $policy = array(
            'returnBody' => $returnBody
        );
        $upToken = $auth->uploadToken($bucket, null, $expires, $policy, true);

        return [
            'token' => $upToken
        ];
    }

}