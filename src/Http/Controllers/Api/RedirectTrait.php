<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-06 15:08
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use function CrCms\Foundation\App\Helpers\combination_url;
use CrCms\Foundation\App\Services\ResponseFactory;
use CrCms\Passport\Handlers\TokenHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Trait RedirectTrait
 * @package CrCms\Passport\Http\Controllers\Api
 */
trait RedirectTrait
{
    /**
     * @param Request $request
     * @param ResponseFactory $response
     * @param TokenHandler|null $token
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function redirect(Request $request, ResponseFactory $response, UserModel $user = null)
    {
        $redirect = $request->input('_redirect');

        if ($user) {
            $token = $this->token($user);
            return $redirect ?
                $response->redirectTo(combination_url($redirect, $token), 301) :
                $response->data($token);
        } else {
            return $redirect ?
                $response->redirectTo($redirect, 301) :
                $response->noContent();
        }
    }

    /**
     * @param UserModel $user
     * @return array
     */
    protected function token(UserModel $user): array
    {
        return (new TokenHandler($user, app(Config::class)))->handle();
    }
}