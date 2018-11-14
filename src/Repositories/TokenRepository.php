<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Repository\AbstractRepository;
use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\TokenModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\Contracts\TokenContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class TokenRepository
 * @package CrCms\Passport\Repositories
 */
class TokenRepository extends AbstractRepository implements TokenContract
{
    /**
     * @var array
     */
    protected $guard = ['token', 'user_id', 'applications', 'expired_at'];

    /**
     * @return TokenModel
     */
    public function newModel(): TokenModel
    {
        return new TokenModel;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function exists(string $token): bool
    {
        return (bool)$this->byStringId($token);
    }

    /**
     * @param string $token
     * @return array
     */
    public function token(string $token): array
    {
        return $this->byStringIdOrFail($token)->toArray();
    }

    /**
     * @param ApplicationModel $application
     * @param UserModel $user
     * @return array
     * @throws \Exception
     */
    public function new(ApplicationModel $application, UserModel $user): array
    {
        $model = parent::create([
            'token' => Str::random(10) . '-' . $user->id . '-' . strval($application->id) . '-' . Str::random(6),
            'applications' => [$application->app_key],
            'user_id' => $user->id,
            'expired_at' => Carbon::now()->addMinute(config()->get('passport.ttl'))->getTimestamp(),
        ]);

        return $model->toArray();
    }

    /**
     * @param string $token
     * @param ApplicationModel $application
     * @return array
     */
    public function increase(ApplicationModel $application, string $token): array
    {
        //** A Bad Code , JSONB Append error */
        $model = $this->token($token);
        $model['applications'][] = $application->app_key;
        $applications = array_unique($model['applications']);

        $model = parent::update(['applications' => $applications], $token);
        return $model->toArray();
    }

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return array
     */
    public function refresh(ApplicationModel $application, string $token): array
    {
        /* @var TokenModel $model */
        $model = $this->token($token);
        $model = parent::update(['expired_at' => config()->get('passport.ttl'), 'refresh_num' => $model['refresh_num'] + 1], $model['token']);
        return $model->toArray();
    }

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return bool
     */
    public function remove(ApplicationModel $application, string $token): bool
    {
        /* @todo 这里还需要条件app_key在这里面才行 */
        return (bool)parent::delete($token);
    }
}