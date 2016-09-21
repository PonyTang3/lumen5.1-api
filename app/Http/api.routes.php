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

$api = app('Dingo\Api\Routing\Router');
// 访问不同版本头里面需要加 Accept:application/vnd.lumen.v2+json
$api->version('v1', function ($api) {
    $api->group(['namespace' => '\App\Http\Controllers\Api\V1'], function ($api) {
        // 测试路由
        $api->get('test', function () {
            return ['name' => 'tang', 'sex' => 'man'];
        });

        $api->post('login', "AuthController@login");

        $api->get('user', 'UserController@user');
        $api->get('users', 'UserController@users');

        // 开启验证
        $api->group(['middleware' => ['jwt.auth']], function ($api) {
            $api->get('token_test', 'AuthController@tokenTest');
        });
    });
});

$api->version('v2', function ($api) {
    $api->get('test', function () {
        return 'v2 test';
    });
});
