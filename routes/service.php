<?php

use Illuminate\Support\Facades\Route;

Route::namespace('CrCms\Passport\Microservice\Controllers')->group(function () {
    Route::register('auth', 'AuthController', ['only' => ['login', 'check', 'refresh', 'user', 'register']]);
    Route::register('user', 'UserController', ['only' => ['index', 'store', 'update', 'destroy']]);
});

//Route::register('login','AuthController@login');
//Route::register('login','AuthController@login');
//Route::register('login','AuthController@login');