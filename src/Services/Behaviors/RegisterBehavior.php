<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/07/06 09:55
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Services\Behaviors\Contracts\BehaviorCreateContract;

/**
 * Class RegisterBehavior
 * @package CrCms\Passport\Services\Behaviors
 */
class RegisterBehavior extends AbstractBehavior implements BehaviorCreateContract
{
    /**
     * @param array $data
     * @return UserBehaviorModel
     */
    public function create(array $data = []): UserBehaviorModel
    {
        $userBehavior = $this->userBehaviorRepository()->create([
            'user_id' => $this->user->id,
            'type' => UserAttribute::AUTH_TYPE_LOGIN,
            'status' => UserAttribute::AUTH_STATUS_SUCCESS,
            'ip' => $data['ip'] ?? '0.0.0.0',
            'agent' => $data['agent'] ?? '',
        ]);

        $this->setUserBehavior($userBehavior);

        return $userBehavior;
    }
}