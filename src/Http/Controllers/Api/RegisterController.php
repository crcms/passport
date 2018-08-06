<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\RegisterHandler;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RedirectTrait;

    /**
     * @param Request $request
     * @param RegisterHandler $handler
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request, RegisterHandler $handler)
    {
        return $this->redirect($request, $this->response, $handler->handle());
    }
}
