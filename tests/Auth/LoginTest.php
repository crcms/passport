<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Exceptions\PassportException;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tests\ApplicationTrait;
use CrCms\Repository\Exceptions\ResourceNotFoundException;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class LoginTest
 * @package CrCms\Passport\Tests\Auth
 */
class LoginTest extends TestCase
{
    use ApplicationTrait;

    protected function getApp($key = null)
    {
        $app = ApplicationModel::first();
        return is_null($key) ? $app : $app->{$key};
    }

    /**
     */
    public function testLoginError()
    {
        $handler = new LoginHandler(new UserRepository());

        try {
            $result = $handler->handle(new DataProvider([
                'name' => Str::random(10),
                'password' => '123456',
                'app_key' => $this->getApp('app_key')
            ]));
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ResourceNotFoundException::class,$exception);
        }
    }

    public function testLoginUserError()
    {
        $user = UserModel::first();

        try {
            $handler = new LoginHandler(new UserRepository());
            $result = $handler->handle(new DataProvider([
                'name' => $user->name,
                'password' => '123456456',
                'app_key' => $this->getApp('app_key')
            ]));
        } catch (\Exception $exception) {
            $this->assertInstanceOf(PassportException::class,$exception);
        }
    }

    public function testLoginSuccess()
    {
        $user = UserModel::whereNotNull('name')->first();

        $handler = new LoginHandler(new UserRepository());

        $result = $handler->handle(new DataProvider([
            'name' => $user->name,
            'password' => '123456',
            'app_key' => $this->getApp('app_key')
        ]));

        $this->assertArrayHasKey('token',$result);
        $this->assertArrayHasKey('token_type',$result);
        $this->assertArrayHasKey('expired',$result);
    }
}