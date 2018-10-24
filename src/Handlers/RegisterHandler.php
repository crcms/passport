<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Http\Request;

class RegisterHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $user = $this->app->make(UserRepository::class)->create($provider->all());

        return $this->registered($provider->get('app_key'), $user);
    }

    /**
     * @param Request $request
     * @param string $appKey
     * @param UserModel $user
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registered(string $appKey, UserModel $user): array
    {
        $this->registeredEvent($user);

        $this->guard->login($user);

        //token
        $tokens = $this->token()->new($this->application($appKey), $user);
        return [
            'jwt' => $this->jwt($this->jwtToken($appKey, $user, $tokens), $tokens['expired_at']),
            'cookie' => $this->cookie($tokens['token'], $tokens['expired_at'])
        ];
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return void
     */
    protected function registeredEvent( UserModel $user): void
    {
        event(new RegisteredEvent(
            $user,
            UserAttribute::AUTH_TYPE_REGISTER
        ));
    }
}