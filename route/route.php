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

// 用户模块
Route::group('user', function () {
    Route::post('register', 'index/UserController/register');
    Route::post('login', 'index/UserController/login');

    Route::post('edit', 'index/UserController/edit')->middleware('auth');
    Route::post('delete', 'index/UserController/delete')->middleware(['auth', 'admin']);

})->allowCrossDomain();

// 文章模块
Route::group('article', function () {

    Route::post('post', 'index/ArticleController/post')->middleware('auth');
    Route::post('edit', 'index/ArticleController/edit')->middleware('auth');
    Route::post('delete', 'index/ArticleController/delete')->middleware('auth');

    Route::get('list', 'index/ArticleController/getList');

})->allowCrossDomain();

// 目录模块
Route::group('category', function () {

    Route::post('add', 'index/CategoryController/add')->middleware('auth');
    Route::post('edit', 'index/CategoryController/edit')->middleware('auth');
    Route::post('delete', 'index/CategoryController/delete')->middleware('auth');
    Route::get('list', 'index/CategoryController/getList')->middleware('auth');

})->allowCrossDomain();

// 相册模块
Route::group('album', function () {

    Route::post('add', 'index/AlbumController/add')->middleware('auth');
    Route::post('edit', 'index/AlbumController/edit')->middleware('auth');
    Route::post('delete', 'index/AlbumController/delete')->middleware('auth');

    Route::get('list/[:userId]', 'index/AlbumController/getList')->pattern(['userId' => '\d+']);

})->allowCrossDomain();

// 图片模块
Route::group('photo', function () {

    Route::post('add', 'index/PhotoController/add')->middleware('auth');
    Route::post('transfer', 'index/PhotoController/transfer')->middleware('auth');
    Route::post('delete', 'index/PhotoController/delete')->middleware('auth');

    Route::get('list/[:userId]', 'index/PhotoController/getList')->pattern(['userId' => '\d+']);

})->allowCrossDomain();


