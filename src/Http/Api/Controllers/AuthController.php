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
use CrCms\Passport\Handlers\CodeHandler;
use CrCms\Passport\Handlers\ForgetPasswordHandler;
use CrCms\Passport\Http\Api\Resources\LoginResource;
use CrCms\Passport\Http\Api\Resources\RegisterResource;
use CrCms\Passport\Http\Api\Requests\Auth\CodeRequest;
use CrCms\Passport\Http\Api\Requests\Auth\ForgetPasswordRequest;
use CrCms\Passport\Http\Api\Requests\Auth\RegisterRequest;
use CrCms\Passport\Handlers\LogoutHandler;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\CheckLoginHandler;
use CrCms\Passport\Handlers\RegisterHandler;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Handlers\UserHandler;
use CrCms\Passport\Http\Api\Resources\UserResource;
use CrCms\Passport\Http\Api\Requests\Auth\CheckLoginRequest;
use CrCms\Passport\Http\Api\Requests\Auth\LoginRequest;
use CrCms\Passport\Http\Api\Requests\Auth\RefreshTokenRequest;
use CrCms\Passport\Http\Api\Requests\Auth\TokenRequest;
use function CrCms\Foundation\App\Helpers\combination_url;
use CrCms\Passport\Http\Api\Requests\Auth\UserRequest;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * 登录
     *
     * @param LoginRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(LoginRequest $request, DataProviderContract $provider)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($provider);

        return $this->response->resource($tokens, LoginResource::class);
    }

    /**
     * 注册
     *
     * @param RegisterRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(RegisterRequest $request, DataProviderContract $provider)
    {
        $tokens = $this->app->make(RegisterHandler::class)->handle($provider);

        return $this->response->resource($tokens, RegisterResource::class);
    }

    /**
     * 获取登录用户token
     *
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
     * 重新刷新token
     *
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
     * 验证是否登录成功
     *
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
     * 获取用户信息
     *
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
     * 用户退出
     *
     * @param UserRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\Response
     */
    public function getLogout(UserRequest $request, DataProviderContract $provider)
    {
        $this->app->make(LogoutHandler::class)->handle($provider);

        return $this->response->noContent();
    }

    /**
     * @param CodeRequest $request
     * @param DataProviderContract $provider
     */
    public function postCode(CodeRequest $request, DataProviderContract $provider)
    {
        $this->app->make(CodeHandler::class)->handle($provider);

        return $this->response->noContent();
    }

    /**
     * @param ForgetPasswordRequest $request
     * @param DataProviderContract $provider
     * @return \Illuminate\Http\Response
     */
    public function postForgetPassword(ForgetPasswordRequest $request, DataProviderContract $provider)
    {
        $this->app->make(ForgetPasswordHandler::class)->handle($provider);

        return $this->response->data();
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