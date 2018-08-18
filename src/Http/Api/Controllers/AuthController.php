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
use CrCms\Passport\Handlers\LogoutHandler;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\SSO\CheckLoginHandler;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Handlers\UserHandler;
use CrCms\Passport\Http\Api\Resources\UserResource;
use CrCms\Passport\Http\Requests\Auth\CheckLoginRequest;
use CrCms\Passport\Http\Requests\Auth\LoginRequest;
use CrCms\Passport\Http\Requests\Auth\RefreshTokenRequest;
use CrCms\Passport\Http\Requests\Auth\TokenRequest;
use function CrCms\Foundation\App\Helpers\combination_url;
use CrCms\Passport\Http\Requests\Auth\UserRequest;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @param DataProviderContract $dataProviderContract
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function postLogin(LoginRequest $request, DataProviderContract $dataProviderContract)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($dataProviderContract);

        $redirect = $request->input('_redirect');

        return $this->responseOrRedirect($redirect, $tokens['jwt'], $tokens['cookie']);
    }

    /**
     * @param TokenRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getToken(TokenRequest $request, DataProviderContract $provider)
    {
        $provider->set('token', $request->cookie('token'));
        $tokens = $this->app->make(TokenHandler::class)->handle($provider);

        //jsonp
        if ((bool)$callback = $request->input('_callback')) {
            return $this->response->jsonp($callback, $tokens);
        }

        return $this->responseOrRedirect($request->input('_redirect'), $tokens);
    }

    /**
     * @param RefreshTokenRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function postRefreshToken(RefreshTokenRequest $request, DataProviderContract $provider)
    {
        $tokens = $this->app->make(RefreshTokenHandler::class)->handle($provider);

        return $this->responseOrRedirect($request->input('_redirect'), $tokens);
    }

    /**
     * @param CheckLoginRequest $request
     * @param DataProviderContract $provider
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function postCheckLogin(CheckLoginRequest $request, DataProviderContract $provider)
    {
        $status = $this->app->make(CheckLoginHandler::class)->handle($provider);

        return $status ? $this->response->noContent() : $this->response->errorUnauthorized();
    }

    /**
     * @param UserRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUser(UserRequest $request, DataProviderContract $provider)
    {
        $user = $this->app->make(UserHandler::class)->handle($provider);

        return $this->response->resource($user, UserResource::class);
    }

    /**
     * @param UserRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\Response
     */
    public function getLogout(UserRequest $request, DataProviderContract $provider)
    {
        $this->app->make(LogoutHandler::class)->handle();

        return $this->response->noContent();
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