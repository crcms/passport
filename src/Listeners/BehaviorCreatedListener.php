<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/07/06 09:24
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners;

use CrCms\Foundation\Helpers\InstanceConcern;
use CrCms\Passport\Events\BehaviorCreatedEvent;
use CrCms\Passport\Repositories\UserBehaviorRepository;
use CrCms\Passport\Services\Behaviors\AbstractBehavior;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class BehaviorCreatedListener
 * @package CrCms\Passport\Listeners
 */
class BehaviorCreatedListener implements ShouldQueue
{
    use InteractsWithQueue, InstanceConcern;

    /**
     * @param BehaviorCreatedEvent $event
     */
    public function handle(BehaviorCreatedEvent $event)
    {
        return $this->behavior($event)->create($event->data);
    }

    /**
     * @return UserBehaviorRepository
     */
    protected function userBehaviorRepository(): UserBehaviorRepository
    {
        return $this->app->make(UserBehaviorRepository::class);
    }

    /**
     * @param BehaviorCreatedEvent $event
     * @return AbstractBehavior
     */
    protected function behavior(BehaviorCreatedEvent $event): AbstractBehavior
    {
        return BehaviorFactory::factory($event->type, $event->user);
    }
}