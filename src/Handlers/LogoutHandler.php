<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\App\Helpers\InstanceTrait;
use Illuminate\Support\Facades\Auth;

/**
 * Class LogoutHandler
 * @package CrCms\Passport\Handlers
 */
class LogoutHandler extends AbstractHandler
{
    use InstanceTrait;

    /**
     * @return void
     */
    public function handle()
    {
        Auth::guard($this->config->get('auth.defaults.guard'))->logout();
    }
}