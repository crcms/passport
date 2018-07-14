<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\LoginAction;
use CrCms\Passport\Actions\TokenAction;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @param LoginAction $action
     * @param TokenAction $tokenAction
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request, LoginAction $action, TokenAction $tokenAction)
    {
        $user = $action->handle();

        $token = $this->token($tokenAction, $user);

        $redirect = $request->input('_redirect');

        return $redirect ?
            $this->response->redirectTo($this->redirectUrl($redirect, $token)) :
            $this->response->data($token);
    }

    /**
     * @param string $url
     * @param array $token
     * @return string
     */
    protected function redirectUrl(string $url, array $token)
    {
        $urlParams = http_build_query($token);
        $joiner = strpos($url, '?') ? '&' : '?';
        return $url . $joiner . $urlParams;
    }

    /**
     * @param UserModel $user
     * @return array
     */
    protected function token(TokenAction $action, UserModel $user): array
    {
        return $action->handle(['user' => $user]);
    }
}
