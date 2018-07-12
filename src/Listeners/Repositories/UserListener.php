<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:16
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners\Repositories;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserListener
 * @package CrCms\Passport\Listeners\Repositories
 */
class UserListener
{
    /**
     * @param UserRepository $userRepository
     * @param array $data
     */
    public function creating(UserRepository $userRepository, array $data)
    {
        $userRepository->addData([
            'password' => Hash::make($data['password']),
            'status' => UserAttribute::STATUS_INACTIVATE,
            'register_ip' => app('request')->ip(),
        ]);
    }
}