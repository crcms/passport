<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-07 09:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors;

use Carbon\Carbon;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Services\Behaviors\Contracts\BehaviorCheckContract;
use CrCms\Passport\Services\Behaviors\Contracts\BehaviorCreateContract;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Class ForgetPasswordBehavior
 * @package CrCms\Passport\Services\Behaviors
 */
class ForgetPasswordMailBehavior extends AbstractBehavior implements BehaviorCreateContract, BehaviorCheckContract
{
    /**
     * @param int $id
     * @return bool
     */
    public function validateRule(int $id): bool
    {
        $userBehavior = $this->userBehaviorRepository()->byIntIdOrFail($id);

        //一堆验证处理


        $this->setUserBehavior($this->update($id));

        return true;
    }

    /**
     * @return string
     */
    public function generateRule(): string
    {
        $urlQuery = (array)$this->userBehavior->extension;

        $urlQuery['behavior_id'] = $this->userBehavior->id;
        $urlQuery['behavior_type'] = $this->userBehavior->type;

        return URL::temporarySignedRoute('user.auth.reset_password.reset', $this->userBehavior->extension->expired_at, $urlQuery);
    }

    /**
     * @param array $data
     * @return UserBehaviorModel
     */
    public function create(array $data = []): UserBehaviorModel
    {
        if (empty($data['token'])) {
            throw new InvalidArgumentException('The token must exist');
        }

        $extensions = [
            'user_email' => $this->user->email,
            'expired_at' => Carbon::now()->addMinutes(config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'))->getTimestamp(),
            'secret' => Str::random(6),
            'token' => $data['token']
        ];

        $userBehavior = $this->userBehaviorRepository()->create([
            'user_id' => $this->user->id,
            'type' => UserAttribute::AUTH_TYPE_FORGET_PASSWORD,
            'status' => UserAttribute::AUTH_STATUS_DEFAULT,
            'ip' => $data['ip'] ?? '0.0.0.0',
            'agent' => $data['agent'] ?? '',
            'extension' => $extensions,
        ]);

        $this->setUserBehavior($userBehavior);

        return $userBehavior;
    }
}