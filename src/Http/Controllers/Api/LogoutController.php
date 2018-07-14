<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-12 12:47
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\LogoutAction;

/**
 * Class LogoutController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class LogoutController extends Controller
{
    /**
     * @param LogoutAction $action
     */
    public function logout(LogoutAction $action)
    {
        $action->handle();

        $this->response->noContent();
    }
}