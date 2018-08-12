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
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\Contracts\TokenContract;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\CookieTask;
use CrCms\Passport\Tasks\JwtTask;
use CrCms\Passport\Models\UserModel;

/**
 * Class TokenHandler
 * @package CrCms\Passport\Handlers
 */
class TokenHandler extends AbstractHandler
{
    /**
     * @var TokenContract
     */
    protected $token;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * TokenHandler constructor.
     * @param TokenContract $token
     * @param UserRepository $repository
     */
    public function __construct(TokenContract $token, UserRepository $repository)
    {
        $this->token = $token;
        $this->repository = $repository;
    }

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $appKey = $provider->get('app_key');

        $tokens = $this->token->get($provider->get('token'));

        // 只执行一次
        /*if (in_array($appKey, $tokens['applications'], true)) {
            return [];
        }*/

        /* @var UserModel $user */
        $user = $this->repository->byIntIdOrFail($tokens['user_id']);

        $application = $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($appKey);

        $tokens = $this->token->increase($application, $user);

//        $cookie = $this->app->make(CookieTask::class)
//            ->handle(CookieTask::TOKEN_INCREASE, $appKey, $tokens['token']);

        $jwtToken = $this->app->make(JwtTask::class)->handle($user, $tokens, $appKey);

        return $jwtToken;
    }
}