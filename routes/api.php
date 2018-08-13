<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware(['api'])->namespace('CrCms\Passport\Http\Api\Controllers')->group(function () {

    Route::post('register', 'RegisterController@postRegister')->name('passport.register.post');
    Route::post('login', 'AuthController@postLogin')->name('passport.login.post');

    Route::post('forget-password', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.forget_password.send_link_email.post');
    Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('passport.forget_password.reset_password_url');
    Route::post('reset-password', 'ResetPasswordController@reset')->name('passport.reset_password.reset');
    Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('passport.behavior_auth.get');

    Route::get('token', 'AuthController@getToken')->name('passport.token.get');
    Route::middleware('auth:api')->get('refresh-token', 'AuthController@getRefreshToken')->name('passport.refresh_token.get');
    Route::middleware('auth:api')->get('logout', 'AuthController@getLogout')->name('passport.logout.get');

});