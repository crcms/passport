<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Repositories\ApplicationRepository;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Class ListHandler
 * @package CrCms\Passport\Handlers\User
 */
final class ListHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return Paginator
     */
    public function handle(DataProviderContract $provider): Paginator
    {
        /* @var ApplicationRepository $repository */
        $repository = $this->app->make(ApplicationRepository::class);

        $app = $repository->byAppKeyOrFail($provider->get('app_key'));

        return $repository->applicationUserPaginate($app, $provider->get('page', 1));
    }
}
