<?php

namespace CrCms\Passport\Mail;

use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var UserModel
     */
    public $user;

    /**
     * @var UserBehaviorModel 
     */
    public $userBehavior;

    /**
     * @var string
     */
    public $code;

    /**
     * RegisterMail constructor.
     * @param UserModel $userModel
     * @param string $code
     */
    public function __construct(UserModel $userModel, UserBehaviorModel $userBehaviorModel,string $code)
    {
        $this->user = $userModel;
        $this->userBehavior = $userBehaviorModel;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('passport::emails.forget_password')->with([
            'code' => $this->code,
        ]);
    }
}
