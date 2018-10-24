<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:16
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners\Repositories;

use CrCms\Foundation\App\Helpers\InstanceTrait;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Repositories\ApplicationRepository;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserListener
 * @package CrCms\Passport\Listeners\Repositories
 */
class UserListener
{
    use InstanceTrait;

    /**
     * @param UserRepository $userRepository
     * @param array $data
     */
    public function creating(UserRepository $userRepository, array $data)
    {
        $newData = [];
        $newData['status'] = UserAttribute::STATUS_INACTIVATE;
        $newData['register_ip'] = app('request')->ip();
        $newData['app_id'] = $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($data['app_key'])->id;
        if (!empty($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }

        $userRepository->addData($newData);
    }
}