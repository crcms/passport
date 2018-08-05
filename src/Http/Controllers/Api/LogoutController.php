<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\LogoutHandler;

/**
 * Class LogoutController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class LogoutController extends Controller
{
    /**
     * @param LogoutHandler $handler
     */
    public function getLogout(LogoutHandler $handler)
    {
        $handler->handle();

        $this->response->noContent();
    }
}