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
use CrCms\Passport\Handlers\Traits\Token;

/**
 * Class LogoutHandler
 * @package CrCms\Passport\Handlers
 */
class LogoutHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): bool
    {
        $payload = $this->guard()->manager()->getJWTProvider()->decode($this->guard()->getToken()->get());

        //remove token
        if ($this->token()->remove(
            $this->application($provider->get('app_key')),
            $payload['token']
        )) {
            //remove jwt token
            $this->guard()->logout();
            return true;
        }

        return false;
    }
}