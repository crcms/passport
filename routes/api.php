<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('passport::api_domain'))
    ->namespace('CrCms\Passport\Http\Controllers\Api')
    ->prefix('api/v1')
    ->middleware(['api'])
    ->group(function () {
        Route::post('register', 'RegisterController@postRegister')->name('passport.register.post');
        Route::post('login', 'LoginController@postLogin')->name('passport.login.post');
        Route::post('forget-password', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.forget_password.send_link_email.post');
        Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('passport.forget_password.reset_password_url');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('passport.reset_password.reset');
        Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('passport.behavior_auth.get');

        Route::middleware('auth:api')->get('logout', 'LogoutController@getLogout')->name('passport.logout.get');
    });

