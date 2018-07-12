<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-08 07:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners;

use CrCms\Passport\Events\ForgetPasswordEvent;
use CrCms\Passport\Mail\ForgetPasswordMail;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Class ForgetPasswordListener
 * @package CrCms\Passport\Listeners
 */
class ForgetPasswordMailListener
{
    use InteractsWithQueue, SerializesModels;

    public function handle(ForgetPasswordEvent $event)
    {
        $registerBehavior = $this->registerBehavior($event);

        $userBehavior = $registerBehavior->create(array_merge($event->data, ['token' => $event->token]));

        Mail::to($event->user->email)
            ->queue(
                (new ForgetPasswordMail($event->user, $userBehavior,$registerBehavior->generateRule()))
                    ->onQueue('emails')
            );
    }

    protected function registerBehavior(ForgetPasswordEvent $event): AbstractBehavior
    {
        return BehaviorFactory::factory($event->type, $event->user);
    }
}