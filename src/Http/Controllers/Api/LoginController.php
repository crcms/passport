<?php

namespace CrCms\Passport\Http\Controllers\Api;

use function CrCms\Foundation\App\Helpers\combination_url;
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
    public function postLogin(Request $request, LoginHandler $loginHandler, Config $config)
    {
        $user = $loginHandler->handle();

        $token = $this->token($user);

        $redirect = $request->input('_redirect');

        return $redirect ?
            $this->response->redirectTo(combination_url($redirect, $token), 301) :
            $this->response->data($token);
    }

    /**
     * @param UserModel $user
     * @return array
     */
    protected function token(UserModel $user): array
    {
        return (new TokenHandler($user, app(Config::class)))->handle();
    }
}
