<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\BehaviorCreatedEvent;
use CrCms\Passport\Models\UserModel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginAction
 * @package CrCms\Passport\Actions
 */
class LoginAction implements ActionContract
{
    use AuthenticatesUsers, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    /**
     * LoginAction constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $fields
     */
    protected function validateLogin(array $fields = [])
    {
        $this->validate($this->request, $fields);
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function validateRules(array $fields = [])
    {
        $all = [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'password' => 'required|string',
            'mobile' => 'required|mobile',
        ];

        return Arr::only($all, $fields ? $fields : ['name', 'password']);
    }

    /**
     * @param Collection|null $collects
     * @return UserModel
     * @throws ValidationException|UnauthorizedException
     */
    public function handle(?Collection $collects = null): UserModel
    {
        $this->validateLogin($collects ? $collects->get('field') : []);

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
        if (!$this->attemptLogin($this->request)) {
            $this->incrementLoginAttempts($this->request);
            return $this->throwLoginError();
        }

        $this->clearLoginAttempts($this->request);

        $user = $this->guard()->user();

        $this->authenticatedEvent($user);

        return $user;
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
}