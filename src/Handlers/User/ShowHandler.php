<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;

class ShowHandler extends AbstractHandler
{
    /**
     * handle
     *
     * @param DataProviderContract $provider
     * @return UserModel
     */
    public function handle(DataProviderContract $provider): UserModel
    {
        /* @var UserRepository $repository */
        $repository = $this->app->make(UserRepository::class);

        // @todo appKey，user_id 应用还未判断
        $appKey = $provider->get('app_key');

        return $repository->byIntIdOrFail($provider->get('id'));
    }
}
