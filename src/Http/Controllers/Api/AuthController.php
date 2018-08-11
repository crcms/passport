<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 13:52
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\CookieTokenCreateHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\JWTTokenHandler;
use CrCms\Passport\Services\Tokens\Contracts\TokenContract;
use Illuminate\Http\Request;
use function CrCms\Foundation\App\Helpers\combination_url;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{

    public function postLogin(Request $request, LoginHandler $login, CookieTokenCreateHandler $cookieTokenCreate, JWTTokenHandler $JWTToken)
    {
        $user = $login->handle();

        $cookieToken = $cookieTokenCreate->handle($user);

        $token = $JWTToken->handle($user, $cookieToken);


        if ((bool)$redirect = $request->input('_redirect')) {

           return $this->response->redirectTo(combination_url($redirect, ['token'=>$token]), 301)->withCookie('token',$cookieToken['token']);

        }

        return $this->response->data(['token'=>$token]);
    }


    public function checkLogin(Request $request)
    {

    }
}