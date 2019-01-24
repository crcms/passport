<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\Jwt\CreateTask;

final class RegisterHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $appKey = $provider->get('app_key');
        /* @var UserRepository $repository */
        $repository = $this->app->make(UserRepository::class);

        // create user
        $user = $repository->setGuard($this->allowFields())->create($provider->all());

        //bind application
        $repository->bindApplication($user, $appKey);

        // events
        $this->registeredEvent($provider, $user);

        // get tokens
        return $this->app->make(CreateTask::class)->handle($user->id, $appKey);
    }

    /**
     * @param DataProviderContract $provider
     * @param UserModel $user
     */
    protected function registeredEvent(DataProviderContract $provider, UserModel $user): void
    {
        $this->event->dispatch(new RegisteredEvent(
            $user,
            UserAttribute::AUTH_TYPE_REGISTER,
            ['ip' => $provider->get('ip', '0.0.0.0'), 'agent' => $provider->get('user_agent')]
        ));
    }

    /**
     * @return array
     */
    protected function allowFields(): array
    {
        return array_merge(array_keys($this->config->get('passport.register_rules')), ['password']);
    }
}