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

class RegisterHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $user = $this->app->make(UserRepository::class)
            ->setGuard(array_keys($this->config->get('passport.register_rules')))
            ->create($provider->all());

        $appKey = $provider->get('app_key');

        $tokens = $this->app->make(CreateTask::class)->handle($user->id, $appKey);

        $this->registeredEvent($provider, $user);

        return $tokens;
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
}