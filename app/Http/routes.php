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

$app->get('/', function () use ($app) {
    phpinfo();
});

$app->get('/test', function () {
    $faker = Faker\Factory::create('zh_CN');
    for ($i = 0; $i < 50; $i++) {
        $users[] = [
            'name'     => $faker->name,
            'email'    => $faker->email,
            'mobile'   => $faker->phoneNumber,
            'password' => bcrypt(str_random(10)),
        ];
    }
    \App\Models\User::insert($users);
});
