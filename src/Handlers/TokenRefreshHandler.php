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
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\Contracts\TokenContract;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;
use Tymon\JWTAuth\JWTGuard;

/**
 * Class TokenRefreshHandler
 * @package CrCms\Modules\passport\src\Handlers
 */
class TokenRefreshHandler extends AbstractHandler
{
    protected $token;

    public function __construct(TokenContract $token)
    {
        $this->token = $token;
    }

    public function handle(DataProviderContract $provider)
    {
        try {
            $payload = $this->guard()->checkOrFail();
        } catch (TokenExpiredException $exception) {
            $payload = $this->guard()->manager()->getJWTProvider()->decode($this->guard()->getToken()->get());
        } catch (Exception $exception) {
            throw new AuthenticationException;
        }

        $appKey = $provider->get('app_key');

        $application = $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($appKey);

        $tokens = $this->token->refresh($application,$payload['token']);

        $token = $this->guard()->setTTL($this->config->get('passport.ttl'))->refresh();
        return ['token'=>$token];
    }

    protected function guard(): JWTGuard
    {
        return $this->auth->guard($this->config->get('auth.defaults.guard'));
    }
}