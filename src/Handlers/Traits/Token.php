<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-14 06:49
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers\Traits;

use CrCms\Foundation\App\Helpers\InstanceConcern;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\Contracts\TokenContract;
use Illuminate\Support\Carbon;

/**
 * Trait Token
 * @package CrCms\Passport\Handlers\Traits
 */
trait Token
{
    use InstanceConcern;

    /**
     * @param string $appKey
     * @return ApplicationModel
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function application(string $appKey): ApplicationModel
    {
        return $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($appKey);
    }

    /**
     * @return TokenContract
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function token(): TokenContract
    {
        return $this->app->make(TokenContract::class);
    }

    /**
     * @param string $token
     * @param int $expired
     * @return array
     */
    protected function jwt(string $token, int $expired): array
    {
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'expired' => $this->expired($expired)
        ];
    }

    /**
     * @param string $token
     * @param int $expired
     * @return array
     */
    protected function cookie(string $token, int $expired): array
    {
        return [
            'name' => 'token',
            'token' => $token,
            'expired' => $this->expired($expired)
        ];
    }

    /**
     * @param int $expired
     * @return int
     */
    protected function expired(int $expired): int
    {
        return Carbon::createFromTimestamp($expired)->diffInMinutes(now());
    }

    /**
     * @param string $appKey
     * @param UserModel $user
     * @param array $tokens
     * @return string
     */
    protected function jwtToken(string $appKey, UserModel $user, array $tokens): string
    {
        return $this->guard()
            ->setTTL($this->expired($tokens['expired_at']))
            ->fromUser($user->setJWTCustomClaims(['token' => $tokens['token'], 'app_key' => $appKey]));
    }
}