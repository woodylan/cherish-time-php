<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/api/dev', ['uses' => 'Dev@run']);

//小程序
$router->group(['prefix' => "/api/weapp/v1/", 'namespace' => 'Weapp'], function () use ($router) {
    //免登录
    $router->group(['prefix' => "user/", 'namespace' => 'User'], function () use ($router) {
        $router->app->any('/login', ['uses' => 'Login@run']);
        $router->app->any('/check-auth', ['uses' => 'CheckAuth@run']);
    });

    $router->group(['middleware' => ['userAuth']], function () use ($router) {

        //时间
        $router->group(['prefix' => "time/", 'namespace' => 'Time'], function () use ($router) {
            $router->app->any('/list', ['uses' => 'GetList@run']);
            $router->app->any('/create', ['uses' => 'Create@run']);
            $router->app->any('/edit', ['uses' => 'Edit@run']);
            $router->app->any('/detail', ['uses' => 'GetDetail@run']);
            $router->app->any('/delete', ['uses' => 'Delete@run']);
        });
    });
});

//通用
$router->group(['prefix' => "common/", 'namespace' => 'Common'], function () use ($router) {
    $router->post('/upload_file', ['uses' => 'UploadFile@run']);
    $router->post('/upload_zip', ['uses' => 'UploadZip@run']);
});

//管理端
$router->group(['prefix' => "/api/admin/v1/", 'namespace' => 'Admin'], function () use ($router) {
    $router->group(['middleware' => ['adminAuth']], function () use ($router) {
        //帐号
        $router->group(['prefix' => "account/", 'namespace' => 'Account'], function () use ($router) {
            $router->post('/check-auth', ['uses' => 'CheckAuth@run']);
        });

        //古诗词
        $router->group(['prefix' => "order/", 'namespace' => 'Time'], function () use ($router) {
            $router->app->any('/list', ['uses' => 'GetList@run']);
            $router->app->any('/create', ['uses' => 'Create@run']);
            $router->app->any('/edit', ['uses' => 'Edit@run']);
            $router->app->any('/detail', ['uses' => 'GetDetail@run']);
            $router->app->any('/delete', ['uses' => 'Delete@run']);
        });
    });
});

//匹配任意路由的options请求
$router->options('/api/{all:.+}', function () {
    return ['code' => 0, 'msg' => '', 'data' => new \stdClass];
});