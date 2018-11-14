<?php

use Illuminate\Support\Facades\Route;

Route::namespace('CrCms\Passport\Microservice\Controllers')->group(function(){
    Route::register('login','AuthController@login');
});

//Route::register('login','AuthController@login');
//Route::register('login','AuthController@login');
//Route::register('login','AuthController@login');