<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 09:23
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Tokens\Contracts;

use CrCms\Passport\Models\ApplicationModel;
use CrCms\Passport\Models\UserModel;

/**
 * Interface TokenContract
 * @package CrCms\Passport\SSO\Contracts
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
     * @param UserModel $user
     * @return array
     */
    public function create(UserModel $user, ApplicationModel $application): array;

    /**
     * @param string $token
     * @return bool
     */
    public function delete(string $token): bool;
}