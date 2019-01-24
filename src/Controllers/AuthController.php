<?php

namespace CrCms\Passport\Controllers;

use CrCms\Microservice\Routing\Controller;
use CrCms\Passport\Handlers\CheckHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Handlers\RegisterHandler;
use CrCms\Passport\Handlers\UserHandler;
use CrCms\Passport\DataProviders\RegisterDataProvider;
use CrCms\Passport\DataProviders\TokenDataProvider;
use CrCms\Passport\Resources\UserResource;
use CrCms\Passport\DataProviders\LoginDataProvider;

/**
 * Class AuthController
 * @package Micr
 */
class AuthController extends Controller
{
    /**
     * @param LoginDataProvider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginDataProvider $provider)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($provider);

        return $this->response->data($tokens);
    }

    /**
     * @param RegisterDataProvider $provider
     * @return mixed
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
        $this->app->make(CheckHandler::class)->handle($provider);

        return $this->response->noContent();
    }

    /**
     * @param TokenDataProvider $provider
     * @return mixed
     */
    public function refresh(TokenDataProvider $provider)
    {
        $tokens = $this->app->make(RefreshTokenHandler::class)->handle($provider);

        return $this->response->data($tokens);
    }

    /**
     * @param TokenDataProvider $provider
     * @return mixed
     */
    public function user(TokenDataProvider $provider)
    {
        $user = $this->app->make(UserHandler::class)->handle($provider);

        return $this->response->resource($user, UserResource::class);
    }
}