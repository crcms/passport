<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware(['api'])->namespace('CrCms\Passport\Http\Api\Controllers')->group(function () {

    Route::post('register', 'AuthController@postRegister')->name('passport.register.post');
    Route::post('login', 'AuthController@postLogin')->name('passport.login.post');

    Route::post('code', 'AuthController@postCode')->name('passport.code.post');
    Route::post('forget-password', 'AuthController@postForgetPassword')->name('passport.forget_password.send_link_email.post');
    Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('passport.forget_password.reset_password_url');
    Route::post('reset-password', 'ResetPasswordController@reset')->name('passport.reset_password.reset');

    Route::get('token', 'AuthController@getToken')->name('passport.token.get');
    Route::post('refresh-token', 'AuthController@postRefreshToken')->name('passport.refresh_token.post');
    Route::post('check-login', 'AuthController@postCheckLogin')->name('passport.check_login.post');

    Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('passport.behavior_auth.get');

    Route::middleware('auth:api')->group(function () {
        Route::post('user', 'AuthController@postUser')->name('passport.user.post');
        Route::get('logout', 'AuthController@getLogout')->name('passport.logout.get');
    });
});

