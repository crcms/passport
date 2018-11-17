<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Repositories\UserRepository;

/**
 * Class UpdateHandler
 * @package CrCms\Passport\Handlers\User
 */
final class UpdateHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return mixed
     */
    public function handle(DataProviderContract $provider)
    {
        return $this->app->make(UserRepository::class)->update($provider->all(), $provider->get('id'));
    }
}