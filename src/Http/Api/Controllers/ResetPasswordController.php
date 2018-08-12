<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Services\Behaviors\BehaviorFactory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) use ($request) {
            $this->behaviorValidate($request, $user);
            $this->resetPassword($user, $password);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return void
     */
    protected function behaviorValidate(Request $request, UserModel $user)
    {
        $behavior = BehaviorFactory::factory(UserAttribute::AUTH_TYPE_FORGET_PASSWORD, $user, $request);
        if (!$behavior->validateRule($request->input('behavior_id'))) {
            return $this->response->errorUnauthorized();
        }
    }

    /**
     * @param $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendResetResponse($response)
    {
        return $this->response->noContent();
    }

    /**
     * @param Request $request
     * @param $response
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        throw new UnprocessableEntityHttpException(trans($response));
    }
}
