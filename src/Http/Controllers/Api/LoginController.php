<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\LoginAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @param LoginAction $action
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws ValidationException
     */
    public function postLogin(LoginAction $action)
    {
        return $this->response->array([
            'data' => [
                'access_token' => Auth::guard()->fromUser($action->handle()),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]
        ]);
    }
}
