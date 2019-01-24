<?php

namespace CrCms\Passport\Handlers\User;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\UserRepository;
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
        $app = $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($provider->get('app_key'));

        return $this->app->make(UserRepository::class)
            ->where('app_id', $app->id)
            ->orderBy('id', 'desc')
            ->paginate();
    }
}