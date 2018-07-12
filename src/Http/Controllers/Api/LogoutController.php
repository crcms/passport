<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LogoutController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class LogoutController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function getLogout(Request $request)
    {
        Auth::guard()->logout();

        $redirect = $request->input('_redirect');

        return $redirect ? redirect($redirect, 301) : $this->response->noContent();
    }
}