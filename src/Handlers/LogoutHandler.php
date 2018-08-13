<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Passport\Handlers\Traits\Token;

/**
 * Class LogoutHandler
 * @package CrCms\Passport\Handlers
 */
class LogoutHandler extends AbstractHandler
{
    use Token;

    /**
     * @return void
     */
    public function handle()
    {
        $this->guard()->logout();
    }
}