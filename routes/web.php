<?php

use Illuminate\Support\Facades\Route;

Route::namespace('CrCms\Passport\Http\Web\Controllers')->group(function () {
    Route::get('login', 'AuthController@getLogin')->name('login');
    Route::get('register', 'AuthController@getRegister')->name('register');
});
