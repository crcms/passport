<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 13:52
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Handlers\Cookie\UpdateHandler;
use CrCms\Passport\Handlers\CookieTokenHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\JWTTokenHandler;
use CrCms\Passport\Services\Tokens\Contracts\TokenContract;
use Illuminate\Http\Request;
use function CrCms\Foundation\App\Helpers\combination_url;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{

    public function postLogin(Request $request, LoginHandler $login, CookieTokenHandler $cookieTokenCreate, JWTTokenHandler $JWTToken)
    {
        $user = $login->handle();

        $cookieToken = $cookieTokenCreate->handle(CookieTokenHandler::TOKEN_CREATE, $user);

        $jwtToken = $JWTToken->handle($user, $cookieToken);

        $redirect = $request->input('_redirect');

        return $this->responseOrRedirect($redirect,$cookieToken,$jwtToken);
    }


    public function getToken(Request $request,UpdateHandler $updateHandler,JWTTokenHandler $JWTTokenHandler)
    {
        $tokens = $updateHandler->handle();



        return $this->response->data(
            empty($tokens) ? $tokens : $JWTTokenHandler->handle($user,$tokens)
        );

//        $token = $request->cookie('token');
//        $application = $request->input('app_key');
//        if ($token && $application) {
//            $data = $tokenContract->get($token);
//            if (in_array($application, $data['applications'])) {
//                return 123;
//            } else {
//                $data['applications'][] = $application;
//                //
////                \Illuminate\Support\Facades\DB::enableQueryLog();
//                $user = \CrCms\Passport\Models\UserModel::where('id', $data['user_id'])->first();
////dd($user,123,\Illuminate\Support\Facades\DB::getQueryLog());
////                $tokenContract->increase($token,$application);
//                \Illuminate\Support\Facades\DB::enableQueryLog();
//                $token = $JWTTokenHandler->handle($user, $tokenContract->increase($token, $application));
//                dd($token, \Illuminate\Support\Facades\DB::getQueryLog());
//
//            }
//        }
    }

    public function getRefreshToken()
    {

    }

    public function getLogout(Request $request)
    {
        $token = $request->cookie('token');
        $application = $request->input('app_key');
        if ($token && $application) {
            $data = $tokenContract->get($token);
            if (in_array($application, $data['applications'])) {
                return 123;
            } else {
                $data['applications'][] = $application;
                //
//                \Illuminate\Support\Facades\DB::enableQueryLog();
                $user = \CrCms\Passport\Models\UserModel::where('id', $data['user_id'])->first();
//dd($user,123,\Illuminate\Support\Facades\DB::getQueryLog());
//                $tokenContract->increase($token,$application);
                \Illuminate\Support\Facades\DB::enableQueryLog();
                $token = $JWTTokenHandler->handle($user, $tokenContract->increase($token, $application));
                dd($token, \Illuminate\Support\Facades\DB::getQueryLog());

            }
        }
    }

    protected function responseOrRedirect(string $redirect, array $cookieToken, array $jwtToken)
    {
        return $redirect ?
            $this->response->redirectTo(combination_url($redirect, $jwtToken), 301)
                ->withCookie('token', $cookieToken['token']) :
            $this->response->data($jwtToken);
    }
}