<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-14 10:05
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Passport\Models\UserModel;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Auth;

class TokenHandler extends AbstractHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var UserModel
     */
    protected $user;

    public function __construct(UserModel $user, Config $config)
    {
        $this->user = $user;
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return array
     */
    public function handle(): array
    {
        return [
            'access_token' => $this->guard()->fromUser($this->user),
            'token_type' => 'Bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->config->get('auth.defaults.guard'));
    }
}