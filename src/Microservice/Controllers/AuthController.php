<?php

namespace CrCms\Passport\Microservice\Controllers;

use CrCms\Microservice\Routing\Controller;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Microservice\DataProviders\LoginDataProvider;
use CrCms\Passport\Microservice\Resources\LoginResource;

/**
 * Class AuthController
 * @package Micr
 */
class AuthController extends Controller
{
    public function login(LoginDataProvider $provider)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($provider);

        return $this->response->resource($tokens, LoginResource::class);
    }
}