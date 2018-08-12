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
     * @param UserModel $user
     * @return array
     */
    public function get(string $token): array;

    /**
     * @param ApplicationModel $application
     * @param UserModel $user
     * @param int $expired
     * @return array
     */
    public function createNew(ApplicationModel $application, UserModel $user, int $expired): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return array
     */
    public function increase(ApplicationModel $application, string $token): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @param int $expired
     * @return array
     */
    public function refresh(ApplicationModel $application, string $token, int $expired): array;

    /**
     * @param ApplicationModel $application
     * @param string $token
     * @return bool
     */
    public function remove(ApplicationModel $application, string $token): bool;
}