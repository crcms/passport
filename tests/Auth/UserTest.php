<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Handlers\UserHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Tests\ApplicationTrait;
use CrCms\Passport\Tests\TokenTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package CrCms\Passport\Tests\Auth
 */
class UserTest extends TestCase
{
    use ApplicationTrait,TokenTrait;

    public function testUser()
    {
        $tokens = $this->token();

        $app = $this->application();

        $handler = new UserHandler();

        $result = $handler->handle(new DataProvider([
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => $tokens['token']
        ]));

        $this->assertInstanceOf(UserModel::class,$result);
        $this->assertEquals($this->user()->name,$result->name);
    }
}