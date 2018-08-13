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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginHandler
 * @package CrCms\Passport\Actions
 */
class LoginHandler extends AbstractHandler
{
    use AuthenticatesUsers, Token;

    /**
     * @var array
     */
    protected $defaultFields = ['name', 'password'];

    /**
     * @var Request
     */
    protected $request;

    /**
     * Handler是不直接接收Request，这里是个特殊，为了直接使用Laravel自带的登录
     * 后期整改掉
     *
     * LoginHandler constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     */
    protected function validateLogin(): void
    {
        $this->validate($this->request, $this->validateRules());
    }

    /**
     * @return array
     */
    protected function validateRules(): array
    {
        $all = [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'password' => 'required|string',
            'mobile' => 'required|mobile',
            //'application_id' => ['required', Rule::exists((new ApplicationModel())->getTable())]
        ];

        return Arr::only($all, $this->defaultFields);
    }

    /**
     * @param mixed ...$params
     * @return UserModel
     * @throws ValidationException|UnauthorizedException
     */
    public function handle(DataProviderContract $provider): array
    {
        $this->validateLogin();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);

            return $this->sendLockoutResponse($this->request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$this->attemptLogin()) {
            $this->incrementLoginAttempts($this->request);
            return $this->throwLoginError();
        }

        $this->clearLoginAttempts($this->request);

        $appKey = $provider->get('app_key');

        /* @var UserModel $user */
        $user = $this->guard()->user();

        $this->authenticatedEvent($user);

        $tokens = $this->token()->new($this->application($appKey), $user);

        return [
            'jwt' => $this->jwt($this->jwtToken($appKey, $user, $tokens), $tokens['expired_at']),
            'cookie' => $tokens
        ];
    }

    /**
     * @return mixed
     */
    protected function attemptLogin()
    {
        return $this->guard()->attempt(
            $this->credentials($this->request), true
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
     * @throws UnauthorizedException
     */
    protected function throwLoginError()
    {
        throw new UnauthorizedException(trans('passport::auth.failed'));
    }

    /**
     * @param UserModel $user
     * @return void
     */
    protected function authenticatedEvent(UserModel $user)
    {
        event(new LoginEvent(
            $user,
            UserAttribute::AUTH_TYPE_LOGIN,
            ['ip' => $this->request->ip(), 'agent' => $this->request->userAgent(), '_redirect' => $this->request->input('_redirect', '')]
        ));
    }

    /**
     *
     */
    protected function sendLockoutResponse()
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($this->request)
        );

        throw ValidationException::withMessages([
            'locked' => [Lang::get('passport::auth.throttle', ['seconds' => $seconds])],
        ])->status(423);
    }
}