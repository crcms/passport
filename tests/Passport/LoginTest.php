<?php

namespace CrCms\Tests\Passport;

use CrCms\Passport\Models\ApplicationModel;
use CrCms\Tests\TestCase;

/**
 * Class LoginTest
 * @package CrCms\Tests\Passport
 */
class LoginTest extends TestCase
{
    public function user()
    {
        return [
            'name' => 'FdKF4pPemw',
            'mobile' => '13512349139',
            'password' => '123456',
        ];
    }

    protected function getApplication(): ApplicationModel
    {
        return ApplicationModel::first();
    }


    public function testNameLogin()
    {
        config([
            'passport.login_rule' => [
                'name' => 'required',
                'password' => 'required|string|min:6',
            ]
        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/login',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'name' => $user['name'],
            'password' => $user['password'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());
        $content = json_decode($response->getContent(),true);
        $this->assertArrayHasKey('jwt',$content['data']);
        $this->assertArrayHasKey('cookie',$content['data']);
    }

    public function testMobileLogin()
    {
        config([
            'passport.login_rule' => [
                'mobile' => 'required',
                'password' => 'required|string|min:6',
            ]
        ]);

        $user = $this->user();

        $response = $this->postJson('api/v1/login',[
            'app_key' => $this->getApplication()->app_key,
            'app_secret' => $this->getApplication()->app_secret,
            'mobile' => $user['mobile'],
            'password' => $user['password'],
        ]);

        dump($response->getStatusCode());
        dump($response->getContent());
        $this->assertEquals(200,$response->getStatusCode());
        $content = json_decode($response->getContent(),true);
        $this->assertArrayHasKey('jwt',$content['data']);
        $this->assertArrayHasKey('cookie',$content['data']);
    }

}