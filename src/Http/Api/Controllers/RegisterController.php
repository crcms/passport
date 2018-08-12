<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\RegisterHandler;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RedirectTrait;

    /**
     * @param Request $request
     * @param RegisterHandler $registerHandler
     * @param LoginHandler $loginHandler
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postRegister(Request $request, RegisterHandler $registerHandler, LoginHandler $loginHandler)
    {
        $registerHandler->handle();

        return $this->redirect($request, $this->response, $loginHandler->handle());
    }
}
