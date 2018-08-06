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
use Illuminate\Support\Facades\Log;

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
     * @var array
     */
    protected $data;

    /**
     * NotificationHandler constructor.
     * @param UserModel $user
     * @param string $redirect
     * @param array $data
     */
    public function __construct(UserModel $user, string $redirect, array $data)
    {
        $this->user = $user;
        $this->redirect = $redirect;
        $this->data = $data;
    }

    /**
     * @return mixed|void
     */
    public function handle()
    {
        $host = parse_url($this->redirect, PHP_URL_HOST);

        if ((bool)$notificationUrl = $this->notifyUrl($host)) {
            $this->notify($notificationUrl);
        }
    }

    /**
     * @param string $host
     * @return string
     */
    protected function notifyUrl(string $host): string
    {
        foreach (config('passport.hosts') as $url) {
            if (stripos($url, $host)) {
                return $url;
            }
        }

        return '';
    }

    /**
     * @param string $notificationUrl
     */
    protected function notify(string $notificationUrl): void
    {
        $resource = curl_init();
        curl_setopt_array($resource, [
            CURLOPT_TIMEOUT => 10,
            CURLOPT_URL => $notificationUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->data
        ]);

        $result = curl_exec($resource);

        if (curl_errno($resource) !== 0) {
            Log::warning("The {$notificationUrl} connection error", $this->data);
        }

        curl_close($resource);
    }
}