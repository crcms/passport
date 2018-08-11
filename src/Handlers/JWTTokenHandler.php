<?php

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Config\Repository as Config;

/**
 * Class JWTTokenHandler
 * @package CrCms\Passport\Handlers
 */
class JWTTokenHandler extends AbstractHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * JWTTokenHandler constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        /* @var UserModel */
        $user = $params[0];

        /* @var array */
        $cookieToken = $params[1];

        $jwtToken = $this->guard()
            ->factory()->setTTL(Carbon::createFromTimestamp($cookieToken['expired_at'])->diffInMinutes(now()))
            ->fromUser($user->setJWTCustomClaims(Arr::only($cookieToken, ['token', 'app_key'])));

        return [
            'token' => $jwtToken,
            'token_type' => 'bearer',
            'expired_at' => $cookieToken['expired_at']
        ];
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->config->get('auth.defaults.guard'));
    }
}