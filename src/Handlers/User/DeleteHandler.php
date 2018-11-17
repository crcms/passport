<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Repositories\UserRepository;

/**
 * Class DeleteHandler
 * @package CrCms\Passport\Handlers\User
 */
final class DeleteHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return int
     */
    public function handle(DataProviderContract $provider): int
    {
        return $this->app->make(UserRepository::class)->delete($provider->get('user'));
    }
}