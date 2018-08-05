<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-06 06:17
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers;

use CrCms\Foundation\App\Http\Controllers\Controller;

/**
 * Class ViewController
 * @package CrCms\Passport\Http\Controllers
 */
class ViewController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return $this->response->view('passport::auth.login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegister()
    {
        return view('passport::auth.register');
    }
}