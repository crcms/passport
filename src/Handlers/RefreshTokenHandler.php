<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 17:51
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\Traits\Token;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;

/**
 * Class RefreshTokenHandler
 * @package CrCms\Modules\passport\src\Handlers
 */
class RefreshTokenHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws AuthenticationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle(DataProviderContract $provider)
    {
        try {
            $payload = $this->guard()->checkOrFail();
        } catch (TokenExpiredException $exception) {
            $payload = $this->guard()->manager()->getJWTProvider()->decode($this->guard()->getToken()->get());
        } catch (Exception $exception) {
            throw new AuthenticationException;
        }

        $tokens = $this->token()->refresh($this->application($provider->get('app_key')), $payload['token']);

        return $this->jwt($this->guard()->setTTL($tokens['expired_at'])->refresh(), $tokens['expired_at']);
    }
}