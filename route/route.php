<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::get('captcha/:timestamp', 'index/CaptchaController/index')->pattern(['timestamp' => '\d+']);

Route::group('', function () {

    // 用户
    Route::group('user', function () {
        Route::post('register', 'index/UserController/register');
        Route::post('login', 'index/UserController/login');

        Route::post('edit', 'index/UserController/edit')->middleware('auth');
        Route::post('delete', 'index/UserController/delete')->middleware(['auth', 'admin']);

        Route::get('detail', 'index/UserController/detail');
    });

    // 文章
    Route::group('article', function () {

        Route::post('post', 'index/ArticleController/post')->middleware('auth');
        Route::post('edit', 'index/ArticleController/edit')->middleware('auth');
        Route::post('delete', 'index/ArticleController/delete')->middleware('auth');

        Route::get('list', 'index/ArticleController/getList');
        Route::get('detail', 'index/ArticleController/detail');

    });

    // 目录模块
    Route::group('category', function () {

        Route::post('add', 'index/CategoryController/add')->middleware('auth');
        Route::post('edit', 'index/CategoryController/edit')->middleware('auth');
        Route::post('delete', 'index/CategoryController/delete')->middleware('auth');
        Route::get('list', 'index/CategoryController/getList')->middleware('auth');

    });

    // 相册模块
    Route::group('album', function () {

        Route::post('add', 'index/AlbumController/add')->middleware('auth');
        Route::post('edit', 'index/AlbumController/edit')->middleware('auth');
        Route::post('delete', 'index/AlbumController/delete')->middleware('auth');

        Route::get('list', 'index/AlbumController/getList');

    });

    // 图片模块
    Route::group('photo', function () {

        Route::post('add', 'index/PhotoController/add')->middleware('auth');
        Route::post('transfer', 'index/PhotoController/transfer')->middleware('auth');
        Route::post('delete', 'index/PhotoController/delete')->middleware('auth');

        Route::get('list', 'index/PhotoController/getList');

    });


    // 留言模块
    Route::group('comment', function () {

        Route::post('add', 'index/CommentController/add')->middleware('auth');
        Route::post('delete', 'index/CommentController/delete')->middleware('auth');

        Route::get('list', 'index/CommentController/getList');

    });

    // 七牛
    Route::group('qiniu', function () {
        Route::get('auth', 'index/QiniuController/auth')->middleware('auth');
    });

})->middleware('header_check')->header([
    'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, my-api-version, my-token',
])->allowCrossDomain();




