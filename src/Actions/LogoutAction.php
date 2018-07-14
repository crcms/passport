<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Foundation\App\Actions\ActionTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class LogoutAction
 * @package CrCms\Passport\Actions
 */
class LogoutAction implements ActionContract
{
    use ActionTrait;

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
     * @param array $data
     * @return mixed|void
     */
    public function handle(array $data = [])
    {
        $this->resolveDefaults($data);

        Auth::guard($this->defaults['guard'])->logout();
    }

    /**
     * @param array $data
     * @return void
     */
    protected function resolveDefaults(array $data): void
    {
        $this->defaults['guard'] = $data['guard'] ?? $this->config->get('auth.defaults.guard');
    }
}