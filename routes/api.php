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
    Route::get('refresh-token', 'AuthController@getRefreshToken')->name('passport.refresh_token.get');
    Route::get('check-login', 'AuthController@getCheckLogin')->name('passport.check_login.get');
    Route::middleware('auth:api')->get('logout', 'AuthController@getLogout')->name('passport.logout.get');

    Route::middleware(function (\Illuminate\Http\Request $request, \Closure $next) {
        //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wYXNzcG9ydC5jcmNtcy5sb2NhbFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE1MzQyNTU0NTAsImV4cCI6MTUzNDI1NTUxMCwibmJ
//        $rpc = app(\CrCms\Foundation\Rpc\Contracts\RpcContract::class);
//        dd($rpc->call('api.v1.refresh-token',['token'=>$request->input('token'),'app_key'=>'1111111111']));
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://passport.crcms.local/api/v1/', 'timeout' => 1]);
        //echo 'refresh-token?token='.$request->input('token').'&app_key='.$request->input('app_key','1111111111');
        try {
            $response = $client->get('check-login', [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'query' => ['token' => $request->input('token'), 'app_key' => '1111111111']
            ]);
            $statusCode = $response->getStatusCode();
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $statusCode = ($exception->getResponse()->getStatusCode());
        }

        if ($statusCode !== 204) {
            echo 'ss';
        }
        dd('abc');
        echo $response->getStatusCode();
        dd($response->getBody());
    })->get('/', function () {
        return 123;
    });
});