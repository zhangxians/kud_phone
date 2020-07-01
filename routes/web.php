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

// 获取数据
Route::get('/login', 'HomeIndex\LoginController@login')->name('login');
Route::post('/login', 'HomeIndex\LoginController@loginin');
Route::get('/logout', 'HomeIndex\LoginController@logout')->middleware('auth');

Route::get('/', 'HomeIndex\IndexController@index')->middleware(['auth','Outbound']);



Route::group(['middleware' => ['auth']], function () {

    Route::get('/sales', 'HomeIndex\SalesController@index');
    Route::get('/order', 'HomeIndex\SalesController@order');
    Route::post('/order/receiving', 'HomeIndex\SalesController@receiving');
    Route::post('/order/update', 'HomeIndex\SalesController@update');

    Route::get('/order/list', 'HomeIndex\SalesController@orderList');

});

// 更新数据
Route::post('/update', 'HomeIndex\IndexController@updateIndex')->middleware('auth');


Route::group(['middleware' => ['auth','Admin']], function () {
    // 用户管理
    Route::get('/user', 'Admin\UserController@user');
    Route::get('/user/page/list', 'Admin\UserController@userPagelist');
    Route::put('/user', 'Admin\UserController@userUpdate');
    Route::post('/user/set/message', 'Admin\UserController@setMessage');
    Route::post('/user/edit', 'Admin\UserController@userEdit');
    Route::post('/user/create', 'Admin\UserController@userCreate');

    // 登录日志
    Route::get('/login/log', 'Admin\UserController@loginLog');
    Route::get('/login/log/list', 'Admin\UserController@loginLogList');


    //
    Route::get('/ip', 'Admin\IndexController@tbIp');
    Route::get('/ip/page/list', 'Admin\IndexController@ipPagelist');
    Route::put('/ip/edit', 'Admin\IndexController@ipUpdate');


    Route::get('/customer', 'Admin\IndexController@customer');
    Route::get('/customer/page/list', 'Admin\IndexController@customerPageList');
    Route::put('/customer', 'Admin\IndexController@customerUpdate');
    Route::get('/customer/list', 'Admin\IndexController@customerList');
    Route::post('/customer/insert', 'Admin\IndexController@customerInsert');


    Route::post('/file/{type?}', 'Admin\FileController@fileUpload');

});