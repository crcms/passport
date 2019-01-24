<?php

namespace CrCms\Passport\Tests\Auth;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Handlers\RegisterHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Tests\ApplicationTrait;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * Class RegisterTest
 * @package CrCms\Passport\Tests\Auth
 */
class RegisterTest extends TestCase
{
    use ApplicationTrait;

    protected function getApp($key = null)
    {
        $app = ApplicationModel::first();
        return is_null($key) ? $app : $app->{$key};
    }

    public function testRegister()
    {
        $handler = new RegisterHandler();

        //$passport = static::$app->make('config')->get('passport');

        $mobile = '1'.strval(mt_rand(10000, 99999)).strval(mt_rand(10000, 99999));

        $result = $handler->handle(new DataProvider([
            'app_key' => $this->getApp('app_key'),
            'password' => '123456',
            'ip' => '127.0.0.1',
            'name' => Str::random(10),
            'mobile' => $mobile,
            'email' => Str::random(20).'@gmail.com',
        ]));

        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('token_type', $result);
        $this->assertArrayHasKey('expired', $result);

        $user = UserModel::whereMobile($mobile)->first();

        $this->assertInstanceOf(UserModel::class,$user);

        $result = UserBehaviorModel::where('user_id',$user->id)->where('type',UserAttribute::AUTH_TYPE_REGISTER)->first();

        $this->assertInstanceOf(UserBehaviorModel::class,$result);


    }
}