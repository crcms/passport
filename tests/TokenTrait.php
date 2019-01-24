<?php

namespace CrCms\Passport\Tests;

use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;

/**
 * Class TokenTrait
 * @package CrCms\Passport\Tests
 */
trait TokenTrait
{
    public function token(): array
    {
        $user = $this->user();
        $app = $this->application();
        $handler = new LoginHandler(new UserRepository());
        return $handler->handle(new DataProvider([
            'name' => $user->name,
            'password' => '123456',
            'app_key' => $app->app_key,
            'app_secret' => $app->app_secret,
        ]));
    }

    public function user()
    {
        return UserModel::first();
    }

    public function application()
    {
        return ApplicationModel::first();
    }
}