<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/5 22:33
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners\Repositories;

use CrCms\Passport\Repositories\UserBehaviorRepository;

class UserBehaviorListener
{
    /**
     * @param UserBehaviorRepository $userBehaviorRepository
     * @param array $data
     */
    public function creating(UserBehaviorRepository $userBehaviorRepository, array $data)
    {
        if (isset($data['extension'])) {
            $userBehaviorRepository->addData([
                'extension' => (object)$data['extension']
            ]);
        }
    }
}