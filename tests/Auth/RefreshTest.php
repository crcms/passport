<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Handlers\RefreshTokenHandler;
use CrCms\Passport\Tests\ApplicationTrait;
use CrCms\Passport\Tests\TokenTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class RefreshTest
 * @package CrCms\Passport\Tests\Auth
 */
class RefreshTest extends TestCase
{
    use ApplicationTrait,TokenTrait;

    public function testRefresh()
    {
        $handler = new RefreshTokenHandler();

        $app = $this->application();
        $tokens = $this->token();

        $tokens = $handler->handle(new DataProvider([
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => $tokens['token']
        ]));

        dd($tokens);
    }
}