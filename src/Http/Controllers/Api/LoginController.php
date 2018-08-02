<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\LoginAction;
use CrCms\Passport\Actions\TokenAction;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Config\Repository as Config;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @param LoginAction $action
     * @param TokenAction $tokenAction
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request, LoginHandler $loginHandler, Config $config)
    {
        $user = $loginHandler->handle();

        $token = (new TokenHandler($user, $config))->handle();

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
    /*protected function token(TokenHandler $handler, UserModel $user): array
    {
        return $handler->handle();
    }*/
}
