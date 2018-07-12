<?php

namespace CrCms\Passport\Mail;

use CrCms\Passport\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class RegisterMail
 * @package CrCms\Passport\Mail
 */
class RegisterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var UserModel
     */
    public $user;

    /**
     * @var string
     */
    public $url;

    /**
     * RegisterMail constructor.
     * @param UserModel $userModel
     * @param string $url
     */
    public function __construct(UserModel $userModel, string $url)
    {
        $this->user = $userModel;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('passport::emails.register')->with([
            'url' => $this->url,
        ]);
    }
}
