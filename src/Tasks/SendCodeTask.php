<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-09-07 11:10
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Tasks;

use CrCms\Foundation\App\Tasks\AbstractTask;
use CrCms\Passport\Events\ForgetPasswordEvent;
use DomainException;

/**
 * Class SendCodeTask
 * @package CrCms\Passport\Tasks
 */
class SendCodeTask extends AbstractTask
{
    /**
     * @param mixed ...$params
     * @return string
     */
    public function handle(...$params): bool
    {
        $object = $params[0];
        $user = $params[1];
        $code = $params[2];
        $type = $params[3];

        if (filter_var($object, FILTER_VALIDATE_EMAIL) !== false) {
            event(new ForgetPasswordEvent($user, $type, $code));
        } elseif (preg_match('/^1[\d]{12}$/', $object) > 0) { //mobile
            /* @todo 手机验证码，需要发送到手机 */
        } else {
            throw new DomainException('Type Error');
        }

        return true;
    }

    /**
     * @return string
     */
    /*protected function randomCode(): string
    {
        return strval(mt_rand(100000, 999999));
    }*/
}