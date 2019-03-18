<?php

use CrCms\Microservice\Dispatching\Facades\Route;

Route::group(['namespace' => 'CrCms\Passport\Controllers'], function () {
    Route::register('auth.register', 'AuthController@register');
    Route::register('auth.login', 'AuthController@login');
    Route::register('auth.check', 'AuthController@check');
    Route::register('auth.refresh', 'AuthController@refresh');
    Route::register('auth.user', 'AuthController@user');

    Route::register('user.index', 'UserController@index');
    Route::register('user.store', 'UserController@store');
    Route::register('user.update', 'UserController@update');
    Route::register('user.destroy', 'UserController@destroy');
    Route::register('user.show', 'UserController@show');
});
