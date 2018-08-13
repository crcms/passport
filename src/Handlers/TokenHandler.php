<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 16:39
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Repositories\UserRepository;

/**
 * Class TokenHandler
 * @package CrCms\Passport\Handlers
 */
class TokenHandler extends AbstractHandler
{
    use Token;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * TokenHandler constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $tokens = $this->token()->token($provider->get('token'));

        $user = $this->repository->byIntIdOrFail($tokens['user_id']);

        return $this->jwt(
            $this->jwtToken($provider->get('app_key'), $user, $tokens),
            $tokens['expired_at']
        );
    }
}