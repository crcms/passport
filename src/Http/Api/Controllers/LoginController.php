<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\CookieTokenHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\JWTTokenHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use RedirectTrait;

    /**
     * @param Request $request
     * @param LoginHandler $handler
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws ValidationException
     */
    public function postLogin(Request $request, LoginHandler $handler)
    {
        $user = $handler->handle();

//        $cookie = (new CookieTokenHandler($user))->handle();
//
//        $token = (new TokenHandler())

        return $this->redirect($request, $this->response, $handler->handle());
    }
}
