<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:34
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * Class BehaviorFactory
 * @package CrCms\Passport\Services\Behaviors
 */
class BehaviorFactory
{
    /**
     * @param int $type
     * @param UserModel $userModel
     * @param Request|null $request
     * @param array $data
     * @return AbstractBehavior
     */
    public static function factory(int $type, UserModel $userModel, ?Request $request = null, array $data = []): AbstractBehavior
    {
        switch ($type) {
            case UserAttribute::AUTH_TYPE_REGISTER_EMAIL_AUTHENTICATION:
                return new RegisterMailBehavior($userModel, $request, $data);
            case UserAttribute::AUTH_TYPE_LOGIN:
                return new LoginBehavior($userModel, $request, $data);
            case UserAttribute::AUTH_TYPE_REGISTER:
                return new RegisterBehavior($userModel, $request, $data);
            case UserAttribute::AUTH_TYPE_FORGET_PASSWORD:
                return new ForgetPasswordMailBehavior($userModel, $request, $data);
            default:
                throw new InvalidArgumentException('Unknown authentication type');
        }
    }
}