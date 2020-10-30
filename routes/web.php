<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/ZG_Account', function () {
    return view('admin.login');
}); 
// 后台首页
Route::group(['prefix' => 'admin'], function () { 
    //页面跳转路由
    Route::get('/main',function () {
        return view('admin.user_index');
    });
    Route::get('/index',function () {
        return view('admin.index');
    });
    Route::get('/userEdit',function () {
        return view('admin.user_edit');
    });
    Route::get('/userAdd',function () {
        return view('admin.user_add');
    });
    Route::get('/sourceAdd',function () {
        return view('admin.source_add');
    });
    Route::get('/sourceList',function () {
        return view('admin.sourceList');
    });
    Route::get('/preview',function () {
        return view('admin.preview');
    });
    // 退出登录
    Route::get('/logout','Admin\LoginController@logout');
    // 登录
    Route::get('/login','Admin\LoginController@login');
    // 验证码
    Route::get('/code','Admin\LoginController@code');
});
Route::group(['prefix' => 'admin','middleware' => 'IsLogin'],function () {
    //添加用户
    Route::post('addUserInfo','Admin\GetUserListController@addUserInfo');
    // 获取用户列表
    Route::get('/getUserList','Admin\GetUserListController@getUserList');
    //修改用户信息
    Route::post('/editUserInfo','Admin\GetUserListController@editUserInfo');
    //删除用户信息
    Route::post('/deleteUserInfo','Admin\GetUserListController@deleteUserInfo');

    //添加资源文件
    Route::post('/uploadSource','Admin\SourceListController@uploadSource');
    //获取资源文件
    Route::get('/getSourceList','Admin\SourceListController@getSourceList');
    //下载模型文件
    Route::get('/downloadSource','Admin\SourceListController@downloadSource');
    //删除模型文件
    Route::post('/deleteSource','Admin\SourceListController@deleteSource');
});