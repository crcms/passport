<?php

namespace CrCms\Passport\Http\Controllers\Api;

use CrCms\Foundation\App\Http\Controllers\Controller;
use CrCms\Passport\Http\Requests\Auth\ResetPasswordUrlRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @param $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendResetLinkResponse( $response)
    {
        return $this->response->noContent();
    }

    /**
     * @param ResetPasswordUrlRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResetPasswordUrl(ResetPasswordUrlRequest $request)
    {
        return $this->response->data([
            'url' => route('user.auth.reset_password.reset', $request->all())
        ]);
    }
}
