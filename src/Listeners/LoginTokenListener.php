<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-05 21:31
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Listeners;

use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class LoginTokenListener
 * @package CrCms\Passport\Listeners
 */
class LoginTokenListener
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * LoginTokenListener constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param LoginEvent $event
     */
    public function handle(LoginEvent $event)
    {
        $tokens = (new TokenHandler($event->user, $this->config))->handle();

        $this->userRepository()->storeLoginInfo($event->user, $tokens);
    }

    /**
     * @return UserRepository
     */
    protected function userRepository(): UserRepository
    {
        return app(UserRepository::class);
    }
}