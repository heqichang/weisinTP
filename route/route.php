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


Route::group('user', function () {
    Route::post('register', 'index/UserController/register');
    Route::post('login', 'index/UserController/login');

    Route::post('edit', 'index/UserController/edit')->middleware('auth');
    Route::post('delete', 'index/UserController/delete')->middleware(['auth', 'admin']);

})->allowCrossDomain();

Route::group('article', function () {

    Route::post('post', 'index/ArticleController/post')->middleware('auth');
    Route::post('edit', 'index/ArticleController/edit')->middleware('auth');
    Route::post('delete', 'index/ArticleController/delete')->middleware('auth');
    Route::get('list', 'index/ArticleController/getList');

})->allowCrossDomain();


