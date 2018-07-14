<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-14 10:05
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Foundation\App\Actions\ActionTrait;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Auth;

/**
 * Class TokenAction
 * @package CrCms\Passport\Actions
 */
class TokenAction implements ActionContract
{
    use ActionTrait;

    /**
     * @var Config
     */
    protected $config;

    /**
     * TokenAction constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return array
     */
    public function handle(array $data = []): array
    {
        $this->resolveDefaults($data);

        return [
            'access_token' => $this->guard()->fromUser($data['user']),
            'token_type' => 'Bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->defaults['guard']);
    }

    /**
     * @param array $data
     */
    protected function resolveDefaults(array $data): void
    {
        $this->defaults['guard'] = $data['guard'] ?? $this->config->get('auth.defaults.guard');
    }
}