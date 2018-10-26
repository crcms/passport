<?php

namespace CrCms\Tests\Passport;

use CrCms\Passport\Models\ApplicationModel;
use CrCms\Tests\TestCase;
use Illuminate\Support\Str;

/**
 * Class RegisterTest
 * @package CrCms\Tests\Passport
 */
class RegisterTest extends TestCase
{
    protected function user(): array
    {
        return [
            'name' => Str::random(10),
            'password' => '123456',
            'mobile' => '1351234'.strval(mt_rand(1111,9999)),
            'email' => Str::random(10).'@gmail.com',
        ];
    }

    protected function getApplication(): ApplicationModel
    {
        return ApplicationModel::first();
    }


    public function testNormalRegister()
    {
        config([
            'passport.register_rules' => [
                'name' => 'required|string|max:15|unique:passport_users',
                'mobile' => 'required|string|max:11|min:11|unique:passport_users',
                'email' => 'required|string|email|max:50|unique:passport_users',
                'password' => 'required|string|min:6',
            ]

        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/register',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'name' => $user['name'],
            'password' => $user['password'],
            'mobile' => $user['mobile'],
            'email' => $user['email'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());

        unset($user['password']);
        $this->assertDatabaseHas('passport_users',array_merge($user,['app_id'=>$this->getApplication()->id]));

    }

    public function testNameRegister()
    {
        config([
            'passport.register_rules' => [
                'name' => 'required|string|max:15|unique:passport_users',
            ]

        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/register',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'name' => $user['name'],
            'password' => $user['password'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());

        unset($user['password']);
        unset($user['email']);
        unset($user['mobile']);
        $this->assertDatabaseHas('passport_users',array_merge($user,['app_id'=>$this->getApplication()->id]));
    }

    public function testMobileRegister()
    {
        config([
            'passport.register_rules' => [
                'mobile' => 'required|string|max:11|min:11|unique:passport_users',
            ]

        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/register',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'mobile' => $user['mobile'],
            'password' => $user['password'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());

        unset($user['password']);
        unset($user['email']);
        unset($user['name']);
        $this->assertDatabaseHas('passport_users',array_merge($user,['app_id'=>$this->getApplication()->id]));
    }

    public function testEmailRegister()
    {
        config([
            'passport.register_rules' => [
                'email' => 'required|string|email|max:50|unique:passport_users',
            ]

        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/register',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'email' => $user['email'],
            'password' => $user['password'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());

        unset($user['password']);
        unset($user['mobile']);
        unset($user['name']);
        $this->assertDatabaseHas('passport_users',array_merge($user,['app_id'=>$this->getApplication()->id]));
    }

}