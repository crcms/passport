<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/7 7:24
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors\Contracts;

use CrCms\Passport\Models\UserBehaviorModel;

/**
 * Interface BehaviorCheckContract
 * @package CrCms\Passport\Services\Behaviors\Contracts
 */
interface BehaviorCheckContract
{
    /**
     * @param int $id
     * @return bool
     */
    public function validateRule(int $id): bool;

    /**
     * @return string
     */
    public function generateRule(): string;
}