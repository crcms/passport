<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-06 14:23
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class NotificationHandler
 * @package CrCms\Passport\Handlers
 */
class NotificationHandler extends AbstractHandler
{
    /**
     * @var string
     */
    protected $redirect;

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * NotificationHandler constructor.
     * @param UserModel $user
     * @param string $redirect
     */
    public function __construct(UserModel $user, string $redirect)
    {
        $this->user = $user;
        $this->redirect = $redirect;
    }

    /**
     * @return mixed|void
     */
    public function handle()
    {
        $host = parse_url($this->redirect, PHP_URL_HOST);
        if ((bool)$notificationUrl = config("passport.hosts.{$host}")) {
            $this->notify($notificationUrl);
        }
    }

    /**
     * @param string $notificationUrl
     */
    protected function notify(string $notificationUrl)
    {
        $resource = curl_init();
        curl_setopt_array($resource, [
            CURLOPT_TIMEOUT => 0.5,
            CURLOPT_URL => $notificationUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->postData()
        ]);
        curl_exec($resource);
        curl_close($resource);
    }

    /**
     * @return array
     */
    protected function postData(): array
    {
        $token = new TokenHandler($this->user, app(Config::class));
        return array_merge($token->handle(), ['name' => $this->user->name]);
    }
}