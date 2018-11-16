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
use CrCms\Microservice\Server\Exceptions\UnprocessableEntityException;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\Jwt\CreateTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginHandler
 * @package CrCms\Passport\Actions
 */
class LoginHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $user = $this->app->make(UserRepository::class)->byNameOrMobileOrEmailOrFail($provider->get($this->username()));

        if (!Hash::check($provider->get('password'), $user->password)) {
            throw new UnprocessableEntityException('name or password error');
        }

        $appKey = $provider->get('app_key');

        $tokens = $this->app->make(CreateTask::class)->handle($user->id, $appKey);

        $this->authenticatedEvent($provider, $user);

        return $tokens;
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return array_first(array_except(array_keys($this->config->get('passport.login_rules')), 'password'));
    }

    /**
     * @param DataProviderContract $provider
     * @param UserModel $user
     */
    protected function authenticatedEvent(DataProviderContract $provider, UserModel $user)
    {
        $this->event->dispatch(new LoginEvent(
            $user,
            UserAttribute::AUTH_TYPE_LOGIN,
            ['ip' => $provider->get('ip', '0.0.0.0'), 'agent' => $provider->get('user_agent')]
        ));
    }
}