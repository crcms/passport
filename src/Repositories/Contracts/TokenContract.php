<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 09:23
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories\Contracts;

use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;

/**
 * Interface TokenContract
 * @package CrCms\Passport\Repositories\Contracts
 */
interface TokenContract
{
    /**
     * @param string $token
     * @return bool
     */
    public function exists(string $token): bool;

    /**
     * @param string $token
     * @return array
     */
    public function token(string $token): array;

    /**
     * @param ApplicationModel $application
     * @param UserModel $user
     * @return array
     */
    public function new(ApplicationModel $application, UserModel $user): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return array
     */
    public function increase(ApplicationModel $application, string $token): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return array
     */
    public function refresh(ApplicationModel $application, string $token): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return bool
     */
    public function remove(ApplicationModel $application, string $token): bool;
}