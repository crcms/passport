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
use Illuminate\Http\Request;

/**
 * Class LogoutController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class LogoutController extends Controller
{
    use RedirectTrait;

    /**
     * @param Request $request
     * @param LogoutHandler $handler
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getLogout(Request $request, LogoutHandler $handler)
    {
        return $this->redirect($request, $this->response, $handler->handle());
    }
}