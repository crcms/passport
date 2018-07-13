<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Actions\RegisterAction;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * @param RegisterAction $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterAction $action)
    {
        $user = $action->handle();//collect(['guard'=>'api'])

        return $this->response->data([
            'access_token' => Auth::guard()->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }
}
