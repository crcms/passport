<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-06 10:59
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Mail\RegisterMail;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegisterMailListener
 * @package CrCms\Passport\Listeners
 */
class RegisterMailListener implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @param RegisteredEvent $registered
     */
    public function handle(RegisteredEvent $registered)
    {
        $registerBehavior = $this->registerBehavior($registered);

        $userBehavior = $registerBehavior->create($registered->data);

        Mail::to($registered->user->email)
            ->queue(
                (new RegisterMail($registered->user, $registerBehavior->generateRule()))
                    ->onQueue('emails')
            );
    }

    /**
     * @param RegisteredEvent $registered
     * @return \CrCms\Passport\Services\Behaviors\AbstractBehavior
     */
    protected function registerBehavior(RegisteredEvent $registered): AbstractBehavior
    {
        return BehaviorFactory::factory(UserAttribute::AUTH_TYPE_REGISTER_EMAIL_AUTHENTICATION, $registered->user);
    }
}