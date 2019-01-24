<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:16
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners\Repositories;

use CrCms\Foundation\Helpers\InstanceConcern;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserListener
 * @package CrCms\Passport\Listeners\Repositories
 */
class UserListener
{
    use InstanceConcern;

    /**
     * @param UserRepository $userRepository
     * @param array $data
     */
    public function creating(UserRepository $userRepository, array $data)
    {
        $newData = [];
        $newData['status'] = UserAttribute::STATUS_INACTIVATE;

        if (!empty($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }

        $userRepository->addData($newData);
    }

    /**
     * @param UserRepository $userRepository
     * @param array $data
     */
    public function updating(UserRepository $userRepository, array $data)
    {
        $storeData = $userRepository->getData();

        $newData = [];
        if (!empty($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        } else {
            unset($storeData['password']);
        }

        $userRepository->setData(array_merge($storeData, $newData));
    }
}