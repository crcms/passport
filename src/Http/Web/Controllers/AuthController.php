<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-14 21:58
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Web\Controllers;

use CrCms\Foundation\App\Http\Controllers\Controller;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Web\Controllers
 */
class AuthController extends Controller
{

    public function getLogin()
    {
        return view('passport::auth.login');
    }


}