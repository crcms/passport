<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;

/**
 * Class StoreHandler
 * @package CrCms\Passport\Handlers\User
 */
final class StoreHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return UserModel
     */
    public function handle(DataProviderContract $provider): UserModel
    {
        return $this->app->make(UserRepository::class)->create($provider->all());
    }
}