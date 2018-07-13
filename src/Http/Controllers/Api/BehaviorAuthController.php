<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:29
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\BehaviorAuthAction;

class BehaviorAuthController extends Controller
{
    /**
     * @param int $id
     * @param BehaviorAuthAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertification(int $id, BehaviorAuthAction $action)
    {
        if (!$action->handle(collect(['id' => $id]))) {
            $this->response->errorUnauthorized();
        }

        $route = $action->getBehaviorService()->getUserBehavior()->extension->redirect ?? null;
        return $this->response->data(['url' => $route ? route($route) : null]);
    }
}