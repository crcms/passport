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
    public $url;

    /**
     * RegisterMail constructor.
     * @param UserModel $userModel
     * @param string $url
     */
    public function __construct(UserModel $userModel, UserBehaviorModel $userBehaviorModel,string $url)
    {
        $this->user = $userModel;
        $this->userBehavior = $userBehaviorModel;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('passport::emails.forget_password')->with([
            'url' => $this->url,
        ]);
    }
}
