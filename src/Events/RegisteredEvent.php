<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/07/06 08:38
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Events;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;

/**
 * Class RegisteredEvent
 * @package CrCms\Passport\Events
 */
class RegisteredEvent extends BehaviorCreatedEvent
{
    /**
     * RegisteredEvent constructor.
     * @param UserModel $userModel
     * @param int $type
     * @param array $data
     */
    public function __construct(UserModel $userModel, int $type, array $data = [])
    {
        parent::__construct($userModel, $type, $data);
        $this->setDefaultData();
    }

    /**
     * @return void
     */
    protected function setDefaultData(): void
    {
        /* @var Request $request */
        $request = app('request');
        $this->data['ip'] = $request->ip();
        $this->data['agent'] = $request->userAgent();
    }
}