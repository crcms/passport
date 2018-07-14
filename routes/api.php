<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('passport::api_domain'))
    ->namespace('CrCms\Passport\Http\Controllers\Api')
    ->prefix('v1')
    ->middleware(['api'])
    ->group(function () {
        Route::post('register', 'RegisterController@register')->name('passport.register.post');
        Route::post('login', 'LoginController@login')->name('passport.login.post');
        Route::post('forget-password', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.forget-password.send-link-email.post');
        Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('user.auth.forget_password.reset_password_url');
        Route::post('reset-password', 'ResetPasswordController@reset')->name('user.auth.reset_password.reset');
        Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('user.auth.behavior_auth.get');

        Route::middleware('auth:api')->get('logout', 'LogoutController@logout')->name('passport.logout.get');
    });

