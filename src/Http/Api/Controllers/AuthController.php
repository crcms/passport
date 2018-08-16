<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 13:52
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Controllers;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\SSO\CheckLoginHandler;
use CrCms\Passport\Handlers\TokenHandler;
use Illuminate\Http\Request;
use function CrCms\Foundation\App\Helpers\combination_url;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param DataProviderContract $dataProviderContract
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function postLogin(Request $request, DataProviderContract $dataProviderContract)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($dataProviderContract);

        $redirect = $request->input('_redirect');

        return $this->responseOrRedirect($redirect, $tokens['jwt'], $tokens['cookie']);
    }

    /**
     * @param Request $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getToken(Request $request, DataProviderContract $provider)
    {
        $provider->set('token', $request->cookie('token'));
        $tokens = $this->app->make(TokenHandler::class)->handle($provider);

        return $this->responseOrRedirect($request->input('_redirect'), $tokens);
    }

    /**
     * @param Request $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getRefreshToken(Request $request, DataProviderContract $provider)
    {
        $tokens = $this->app->make(RefreshTokenHandler::class)->handle($provider);
        return $this->responseOrRedirect($request->input('_redirect'), $tokens);
    }

    /**
     * @param Request $request
     * @param DataProviderContract $provider
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCheckLogin(Request $request, DataProviderContract $provider)
    {
        $status = $this->app->make(CheckLoginHandler::class)->handle($provider);

        return $status ? $this->response->noContent() : $this->response->errorUnauthorized();
    }

    /**
     * @param DataProviderContract $provider
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getLogout(DataProviderContract $provider)
    {
        $this->app->make(LoginHandler::class);
    }

    /**
     * @param null|string $redirect
     * @param array $jwtToken
     * @param array $cookieToken
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseOrRedirect(?string $redirect, array $jwtToken, array $cookieToken = [])
    {
        $response = $redirect ?
            $this->response->redirectTo(combination_url($redirect, $jwtToken), 301) :
            $this->response->data($jwtToken);

        /* @todo 还需要对Cookie进行有效期限制 */
        return empty($cookieToken) ? $response : $response->withCookie('token', $cookieToken['token']);
    }
}