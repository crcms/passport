<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Foundation\App\Actions\ActionTrait;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\BehaviorCreatedEvent;
use CrCms\Passport\Models\UserModel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class LoginAction
 * @package CrCms\Passport\Actions
 */
class LoginAction implements ActionContract
{
    use AuthenticatesUsers, ValidatesRequests, ActionTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Config
     */
    protected $config;

    /**
     * LoginAction constructor.
     * @param Request $request
     * @param Config $config
     */
    public function __construct(Request $request, Config $config)
    {
        $this->request = $request;
        $this->config = $config;
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
        ];

        return Arr::only($all, $this->defaults['fields']);
    }

    /**
     * @param Collection|null $collects
     * @return UserModel
     * @throws ValidationException|UnauthorizedException
     */
    public function handle(array $data = []): UserModel
    {
        $this->resolveDefaults($data);

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

        $user = $this->guard()->user();

        $this->authenticatedEvent($user);

        return $user;
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
        return $this->defaults['username'];
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
        event(new BehaviorCreatedEvent(
            $user,
            UserAttribute::AUTH_TYPE_LOGIN,
            ['ip' => $this->request->ip(), 'agent' => $this->request->userAgent()]
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

    /**
     * @param string $guard
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->defaults['guard']);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function resolveDefaults(array $data): void
    {
        $this->defaults['guard'] = $data['guard'] ?? $this->config->get('auth.defaults.guard');
        $this->defaults['fields'] = $data['fields'] ?? ['name', 'password'];
        $this->defaults['username'] = $data['username'] ?? 'name';
    }
}