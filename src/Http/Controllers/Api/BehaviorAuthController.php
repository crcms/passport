<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 20:29
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Http\Request;

class BehaviorAuthController extends Controller
{
    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertification(int $id, Request $request)
    {
        $user = UserModel::find($request->input('user_id'));

        $behaviorService = BehaviorFactory::factory($request->input('behavior_type'), $user, $request);
        if (!$behaviorService->validateRule($id)) {
            return $this->response->errorUnauthorized();
        }

        $route = $behaviorService->getUserBehavior()->extension->redirect ?? null;
        return $this->response->data(['url' => $route ? route($route) : null]);
    }
}