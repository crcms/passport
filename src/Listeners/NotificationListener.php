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
use CrCms\Passport\Handlers\TicketHandler;
use Illuminate\Contracts\Config\Repository as Config;

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
        $token = new TicketHandler($event->user, app(Config::class));
        $data = array_merge($token->handle(), ['name' => $event->user->name]);
        $handler = new NotificationHandler($event->user, $event->data['_redirect'], $data);
        $handler->handle();
    }
}