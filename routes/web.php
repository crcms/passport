<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('passport::domain'))
    ->namespace('CrCms\Passport\Http\Controllers')
    ->group(function () {
        Route::get('login', 'ViewController@getLogin')->name('login');
        Route::get('register', 'ViewController@getRegister');
    });
