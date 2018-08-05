<?php

namespace CrCms\Passport\Http\Controllers\Api;

use function CrCms\Foundation\App\Helpers\combination_url;
use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\RegisterHandler;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;

class RegisterController extends Controller
{
    public function getRegister()
    {
        return view('passport::auth.register');
    }

    /**
     * @param Request $request
     * @param RegisterHandler $handler
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request, RegisterHandler $handler)
    {
        $user = $handler->handle();

        $token = $this->token($user);

        $redirect = $request->input('_redirect');

        return $redirect ?
            $this->response->redirectTo(combination_url($redirect, $token)) :
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
