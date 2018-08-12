<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-11 13:52
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Http\Api\Controllers;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\TokenRefreshHandler;
use CrCms\Passport\Handlers\LoginHandler;
use CrCms\Passport\Handlers\TokenHandler;
use Illuminate\Http\Request;
use function CrCms\Foundation\App\Helpers\combination_url;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 * @package CrCms\Passport\Http\Controllers\Api
 */
class AuthController extends Controller
{

    public function postLogin(Request $request, DataProviderContract $dataProviderContract)
    {
        $tokens = $this->app->make(LoginHandler::class)->handle($dataProviderContract);

        $redirect = $request->input('_redirect');

        return $this->responseOrRedirect($redirect, $tokens['jwt'], $tokens['cookie']);
    }

    public function getToken(Request $request, DataProviderContract $provider)
    {
        $provider->set('token', $request->cookie('token'));
        $tokens = $this->app->make(TokenHandler::class)->handle($provider);

        return $this->responseOrRedirect($request->input('_redirect'), $tokens);
    }
//
//    public function getRefreshToken()
//    {
//
//    }
//
//    public function getLogout(Request $request)
//    {
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
//    }

    public function getLogout(DataProviderContract $provider)
    {
        $provider->set('app_key','2222222222');
        dd($this->app->make(TokenRefreshHandler::class)->handle($provider));

//        if (!Auth::guard()->check()) {
//            dd(Auth::guard()->manager()->getJWTProvider()->decode(Auth::guard()->getToken()->get()));
//        }
//        dd(Auth::guard()->manager()->decode(Auth::guard()->getToken()));
//        dd(Auth::guard()->parser()->getChain());
    }


    protected function responseOrRedirect(?string $redirect, array $jwtToken, array $cookieToken = [])
    {
        $response = $redirect ?
            $this->response->redirectTo(combination_url($redirect, $jwtToken), 301) :
            $this->response->data($jwtToken);

        /* @todo 还需要对Cookie进行有效期限制 */
        return empty($cookieToken) ? $response : $response->withCookie('token', $cookieToken['token']);
    }
}