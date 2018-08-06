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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TicketHandler extends AbstractHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * TicketHandler constructor.
     * @param UserModel $user
     * @param Config $config
     */
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
            'ticket' => base64_encode(Hash::make(now()->getTimestamp() . strval(Str::random(6)) . strval($this->user->id) . $this->user->password . $this->config->get('app.key'))),
            'ticket_expired_at' => Carbon::now()->getTimestamp() + $this->guard()->factory()->getTTL() * 60
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