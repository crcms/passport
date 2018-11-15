<?php

namespace CrCms\Passport\Microservice\Controllers;

use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Microservice\Routing\Controller;
use CrCms\Passport\Handlers\CheckLoginHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Handlers\RegisterHandler;
use CrCms\Passport\Handlers\UserHandler;
use CrCms\Passport\Microservice\DataProviders\RegisterDataProvider;
use CrCms\Passport\Microservice\DataProviders\TokenDataProvider;
use CrCms\Passport\Microservice\Resources\UserResource;
use CrCms\Passport\Microservice\DataProviders\LoginDataProvider;

/**
 * Class AuthController
 * @package Micr
 */
class AuthController extends Controller
{
    /**
     * @param LoginDataProvider $provider
     * @return mixed
     */
    public function login(LoginDataProvider $provider)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($provider);

        return $this->response->data($tokens);
    }

    /**
     * @param RegisterDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterDataProvider $provider)
    {
        $tokens = $this->app->make(RegisterHandler::class)->handle($provider);

        return $this->response->data($tokens);
    }

    /**
     * @param TokenDataProvider $provider
     * @return mixed
     */
    public function check(TokenDataProvider $provider)
    {
        return $this->app->make(CheckLoginHandler::class)->handle($provider);
    }

    /**
     * @param TokenDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(TokenDataProvider $provider)
    {
        $tokens = $this->app->make(RefreshTokenHandler::class)->handle($provider);

        return $this->response->data($tokens);
    }

    /**
     * @param TokenDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(TokenDataProvider $provider)
    {
        $user = $this->app->make(UserHandler::class)->handle($provider);

        return $this->response->resource($user, UserResource::class);
    }
}