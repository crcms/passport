<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/07/06 09:24
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Events;

use CrCms\Passport\Models\UserModel;

/**
 * Class BehaviorCreatedEvent
 * @package CrCms\Passport\Events
 */
class BehaviorCreatedEvent
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var UserModel
     */
    public $user;

    /**
     * @var int
     */
    public $type;

    /**
     * BehaviorCreatedEvent constructor.
     * @param UserModel $userModel
     * @param int $type
     * @param array $data
     */
    public function __construct(UserModel $userModel, int $type, array $data = [])
    {
        $this->user = $userModel;
        $this->type = $type;
        $this->data = $data;
    }
}