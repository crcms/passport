<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Handlers\CheckHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tests\ApplicationTrait;
use CrCms\Passport\Tests\TokenTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckTest
 * @package CrCms\Passport\Tests\Auth
 */
class CheckTest extends TestCase
{
    use ApplicationTrait,TokenTrait;


    /**
     */
    public function testCheck()
    {
        $tokens = $this->token();
        $app = ApplicationModel::first();

        $handler = new CheckHandler();

        $result = $handler->handle(new DataProvider([
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => $tokens['token']
        ]));

        $this->assertEquals(true,$result);
    }

}