<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\LoginHandler;
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
        return $this->redirect($request, $this->response, $handler->handle());
    }
}
