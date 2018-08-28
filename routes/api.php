<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware(['api'])->namespace('CrCms\Passport\Http\Api\Controllers')->group(function () {

    Route::post('register', 'AuthController@postRegister')->name('passport.register.post');
    Route::post('login', 'AuthController@postLogin')->name('passport.login.post');

    Route::post('forget-password', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.forget_password.send_link_email.post');
    Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('passport.forget_password.reset_password_url');
    Route::post('reset-password', 'ResetPasswordController@reset')->name('passport.reset_password.reset');
    Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('passport.behavior_auth.get');

    Route::get('token', 'AuthController@getToken')->name('passport.token.get');
    Route::post('refresh-token', 'AuthController@postRefreshToken')->name('passport.refresh_token.post');
    Route::post('check-login', 'AuthController@postCheckLogin')->name('passport.check_login.post');
    //
    Route::middleware('auth:api')->post('user', 'AuthController@postUser')->name('passport.user.post');
    Route::middleware('auth:api')->get('logout', 'AuthController@getLogout')->name('passport.logout.get');

    Route::get('/',function(\Illuminate\Http\Request $request){
        dd($request->all());
    });
//
//    Route::middleware(function (\Illuminate\Http\Request $request, \Closure $next) {
//        //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wYXNzcG9ydC5jcmNtcy5sb2NhbFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE1MzQyNTU0NTAsImV4cCI6MTUzNDI1NTUxMCwibmJ
////        $rpc = app(\CrCms\Foundation\Rpc\Contracts\RpcContract::class);
////        dd($rpc->call('api.v1.refresh-token',['token'=>$request->input('token'),'app_key'=>'1111111111']));
//        $client = new \GuzzleHttp\Client(['base_uri' => 'http://passport.crcms.local/api/v1/', 'timeout' => 1]);
//        //echo 'refresh-token?token='.$request->input('token').'&app_key='.$request->input('app_key','1111111111');
//        try {
//            $response = $client->get('check-login', [
//                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
//                'query' => ['token' => $request->input('token'), 'app_key' => '1111111111']
//            ]);
//            $statusCode = $response->getStatusCode();
//        } catch (\GuzzleHttp\Exception\ClientException $exception) {
//            $statusCode = ($exception->getResponse()->getStatusCode());
//        }
//
//        if ($statusCode !== 204) {
//            echo 'ss';
//        }
//        dd('abc');
//        echo $response->getStatusCode();
//        dd($response->getBody());
//    })->get('/', function () {
//        return 123;
//    });

    Route::get('test-refresh',function(\Illuminate\Http\Request $request,\CrCms\Foundation\Sso\Client\Contracts\InteractionContract $contract){

        $data = $contract->refresh($request->input('token','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wYXNzcG9ydC5jcmNtcy5sb2NhbFwvYXBpXC92MVwvdG9rZW4iLCJpYXQiOjE1MzQ1MTMyMzQsImV4cCI6MTUzNDUxNDAxNCwibmJmIjoxNTM0NTEzMjM0LCJqdGkiOiJXN1E4TUVqZWlkYUw4Y2hNIiwic3ViIjoxLCJwcnYiOiI3MjkyN2M2ZjJjYzk2MmVmYTc4NTNjOTM2ZTEwZGNjNjI5ZDcxN2U0IiwidG9rZW4iOiJ2VWZzUXZKdG90LTEtMS1EZVJrSlAiLCJhcHBfa2V5IjoiMjIyMjIyMjIyMiJ9.GOGrgO4tAN9eZCBQsB1Ye5VBuJhXfmSbaMnDrK2Zv60'));
        dd($data);

//        try {
//            $response = $contract->call('user.token');
//            dd($response);
//        } catch (\Exception $exception) {
//            dd($exception->getMessage());
//        }


    });
});

