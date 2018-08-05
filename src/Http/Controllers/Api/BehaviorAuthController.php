<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:29
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\BehaviorAuthHandler;

class BehaviorAuthController extends Controller
{
    /**
     * @param BehaviorAuthHandler $handler
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertification(BehaviorAuthHandler $handler, int $id)
    {
        if (!$handler->handle()) {
            $this->response->errorUnauthorized();
        }

        $route = $handler->getBehaviorService()->getUserBehavior()->extension->redirect ?? null;
        return $this->response->data(['url' => $route ? route($route) : null]);
    }
}