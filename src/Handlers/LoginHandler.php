<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Models\UserModel;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginHandler
 * @package CrCms\Passport\Actions
 */
class LoginHandler extends AbstractHandler
{
    use ThrottlesLogins, Token;

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        /* @todo Handler是不直接接收Request，这里是个特殊，为了直接使用Laravel自带的登录 */
        $request = $this->app->make(Request::class);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->throwLockout($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$this->attemptLogin($request)) {
            $this->incrementLoginAttempts($request);
            return $this->throwLoginError();
        }

        $this->clearLoginAttempts($request);

        /* @var UserModel $user */
        $user = $this->guard()->user();

        return $this->authenticated($request, $provider->get('app_key'), $user);
    }

    /**
     * @return mixed
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), true
        );
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return 'name';
    }

    /**
     * @throws ValidationException
     */
    protected function throwLoginError()
    {
        throw ValidationException::withMessages([
            'failed' => trans('passport::auth.failed'),
        ])->status(401);
    }

    /**
     * @param Request $request
     * @param UserModel $user
     */
    protected function authenticatedEvent(Request $request, UserModel $user)
    {
        event(new LoginEvent(
            $user,
            UserAttribute::AUTH_TYPE_LOGIN,
            ['ip' => $request->ip(), 'agent' => $request->userAgent(), '_redirect' => $request->input('_redirect', '')]
        ));
    }

    /**
     * @throws ValidationException
     */
    protected function throwLockout(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'locked' => [Lang::get('passport::auth.throttle', ['seconds' => $seconds])],
        ])->status(423);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * @param Request $request
     * @param string $appKey
     * @param UserModel $user
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function authenticated(Request $request, string $appKey, UserModel $user): array
    {
        //event
        $this->authenticatedEvent($request, $user);

        //token
        $tokens = $this->token()->new($this->application($appKey), $user);
        return [
            'jwt' => $this->jwt($this->jwtToken($appKey, $user, $tokens), $tokens['expired_at']),
            'cookie' => $this->cookie($tokens['token'], $tokens['expired_at'])
        ];
    }
}