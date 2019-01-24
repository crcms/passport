<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Exceptions\PassportException;
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

    /**
     * @expectedException \CrCms\Passport\Exceptions\PassportException
     * @expectedExceptionMessage Token error
     */
    public function testRefresh()
    {
        $handler = new RefreshTokenHandler();

        $app = $this->application();
        $oldTokens = $this->token();

        $tokens = $handler->handle(new DataProvider([
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => $oldTokens['token']
        ]));

        $this->assertEquals(true,is_array($tokens));
        $this->assertArrayHasKey('token',$tokens);
        $this->assertArrayHasKey('token_type',$tokens);
        $this->assertArrayHasKey('expired',$tokens);

        $twoTokens = $handler->handle(new DataProvider([
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => $oldTokens['token']
        ]));
    }
}