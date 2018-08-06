<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-06 14:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners;

use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Handlers\NotificationHandler;

/**
 * Class NotificationListener
 * @package CrCms\Passport\Listeners
 */
class NotificationListener
{
    /**
     * @param LoginEvent $event
     */
    public function handle(LoginEvent $event)
    {
        $handler = new NotificationHandler($event->user, $event->data['_redirect']);
        $handler->handle();
    }
}