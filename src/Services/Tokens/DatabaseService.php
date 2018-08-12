<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 13:34
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Tokens;

use Carbon\Carbon;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\TokenRepository;
use CrCms\Passport\Services\Tokens\Contracts\TokenContract;
use Illuminate\Support\Str;

/**
 * Class DatabaseService
 * @package CrCms\Passport\Services\Tokens
 */
class DatabaseService implements TokenContract
{
    /**
     * @var TokenRepository
     */
    protected $repository;

    /**
     * DatabaseService constructor.
     * @param TokenRepository $repository
     */
    public function __construct(TokenRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function exists(string $token): bool
    {
        return (bool)$this->repository->byStringId($token);
    }

    /**
     * @param string $token
     * @return array
     */
    public function get(string $token): array
    {
        $model = $this->repository->byStringIdOrFail($token);
        return $model->toArray();
    }

    /**
     * @param UserModel $user
     * @param ApplicationModel $application
     * @return array
     */
    public function create(UserModel $user, ApplicationModel $application): array
    {
        $model = $this->repository->create([
            'token' => Str::random(10) . '-' . $user->id . '-' . strval($application->id) . '-' . Str::random(6),
            'applications' => [$application->app_key],
            'user_id' => $user->id,
            //有效期暂时为1天，后期配置，还要增加refresh token次数
            'expired_at' => Carbon::now()->addDay(1)->getTimestamp(),
        ]);

        return $model->toArray();
    }

    /**
     * @param string $token
     * @param ApplicationModel $application
     * @return array
     */
    public function increase(string $token, ApplicationModel $application): array
    {
        //** A Bad Code , JSONB Append error */
        $model = $this->get($token);
        $model['applications'][] = $application->app_key;
        $applications = array_unique($model['applications']);

        $model = $this->repository->update(['applications' => $applications], $token);
        return $model->toArray();
    }

    /**
     * @param string $token
     * @return bool
     */
    public function delete(string $token): bool
    {
        return (bool)$this->repository->delete($token);
    }
}