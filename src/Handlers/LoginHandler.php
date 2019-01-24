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
use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Exceptions\PassportException;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\Jwt\CreateTask;
use CrCms\Passport\Tasks\UserApplicationTask;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginHandler
 * @package CrCms\Passport\Actions
 */
final class LoginHandler extends AbstractHandler
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * LoginHandler constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $user = $this->repository->byNameOrMobileOrEmailOrFail($provider->get($this->username()));

        $appKey = $provider->get('app_key');

        $this->checkPassword($user, $provider->get('password'));
        $this->checkApplication($user, $appKey);

        $tokens = $this->app->make(CreateTask::class)->handle($user->id, $appKey);

        $this->authenticatedEvent($provider, $user);

        return $tokens;
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return Arr::first(Arr::except(array_keys($this->config->get('passport.login_rules')), 'password'));
    }

    /**
     * @param UserModel $user
     * @param string $appKey
     *
     * @throws PassportException
     * @return void
     */
    protected function checkApplication(UserModel $user, string $appKey): void
    {
        $applications = $this->app->make(UserApplicationTask::class)->handle($user);

        if (!$applications->contains('app_key', $appKey)) {
            throw new PassportException('应用范围错误');
        }
    }

    /**
     * @param UserModel $user
     * @param string $provider
     *
     * @throws PassportException
     * @return void
     */
    protected function checkPassword(UserModel $user, string $password): void
    {
        if (!Hash::check($password, $user->password)) {
            throw new PassportException('用户名或密码错误');
        }
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