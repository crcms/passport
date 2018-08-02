<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class LogoutHandler
 * @package CrCms\Passport\Handlers
 */
class LogoutHandler extends AbstractHandler
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * LogoutAction constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed|void
     */
    public function handle()
    {
        Auth::guard($this->config->get('auth.defaults.guard'))->logout();
    }
}