<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware(['api'])->namespace('CrCms\Passport\Http\Api\Controllers')->group(function () {

    Route::post('register', 'RegisterController@postRegister')->name('passport.register.post');
    Route::post('login', 'AuthController@postLogin')->name('passport.login.post');

    Route::post('forget-password', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.forget_password.send_link_email.post');
    Route::post('reset-password-url', 'ForgotPasswordController@postResetPasswordUrl')->name('passport.forget_password.reset_password_url');
    Route::post('reset-password', 'ResetPasswordController@reset')->name('passport.reset_password.reset');
    Route::middleware('signed')->get('behavior-auth/{behavior_id}', 'BehaviorAuthController@getCertification')->name('passport.behavior_auth.get');

    Route::get('logout', 'AuthController@getLogout')->name('passport.logout.get');
    Route::middleware('auth:api')->get('test',function(\Illuminate\Http\Request $request){
        return \Illuminate\Support\Facades\Auth::user();
    });


    Route::get('passport2','AuthController@getToken');

//    Route::get('passport2',function(\Illuminate\Http\Request $request,\CrCms\Passport\Services\Tokens\Contracts\TokenContract $tokenContract,\CrCms\Passport\Handlers\JWTTokenHandler $JWTTokenHandler){
//       $token = $request->cookie('token');
//        $application = $request->input('app_key',222222222);
//       if ($token) {
//           $data = $tokenContract->get($token);
//            if (in_array($application,$data['applications'])) {
//                return 123;
//            } else {
//                $data['applications'][] = $application;
//                //
////                \Illuminate\Support\Facades\DB::enableQueryLog();
//                $user = \CrCms\Passport\Models\UserModel::where('id',$data['user_id'])->first();
////dd($user,123,\Illuminate\Support\Facades\DB::getQueryLog());
////                $tokenContract->increase($token,$application);
//\Illuminate\Support\Facades\DB::enableQueryLog();
//                $token = $JWTTokenHandler->handle($user,$tokenContract->increase($token,$application));
//                dd($token,\Illuminate\Support\Facades\DB::getQueryLog());
//
//            }
//       }
//
//    });



});

    Route::get('test',function(\Illuminate\Http\Request $request){
        return 123;
    });