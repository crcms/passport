<?php

namespace CrCms\Passport\Tests\Auth\DataProvider;

use CrCms\Passport\DataProviders\TokenDataProvider;
use CrCms\Passport\Tests\ApplicationTrait;
use CrCms\Passport\Tests\TokenTrait;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenDataProviderTest
 * @package CrCms\Passport\Tests\Auth\DataProvider
 */
class TokenDataProviderTest extends TestCase
{
    use ApplicationTrait, TokenTrait;

    public function testTokenDataProvider()
    {
        $app = $this->application();

        $data = [
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
            'token' => Str::random(10)
        ];

        $request = \Mockery::mock('request');
        $request->shouldReceive('all')->andReturn($data);

        static::$app->instance('request', $request);

        $provider = new TokenDataProvider($data);

        $provider->validateResolved();

        $this->assertEquals(true,true);
    }


    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testTokenExceptionDataProvider()
    {
        $app = $this->application();

        $data = [
            'app_secret' => $app->app_secret,
            'token' => Str::random(10)
        ];

        $request = \Mockery::mock('request');
        $request->shouldReceive('all')->andReturn($data);

        static::$app->instance('request', $request);

        $provider = new TokenDataProvider($data);

        $provider->validateResolved();
    }
}