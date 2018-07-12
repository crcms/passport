<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 6:07
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors\Contracts;

use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;

interface BehaviorCreateContract
{
    /**
     * @param array $data
     * @return UserBehaviorModel
     */
    public function create(array $data = []): UserBehaviorModel;
}