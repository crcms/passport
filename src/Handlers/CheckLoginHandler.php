<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-15 06:27
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\Traits\Token;

/**
 * Class CheckLoginHandler
 * @package CrCms\Passport\Handlers\SSO
 */
class CheckLoginHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return bool
     */
    public function handle(DataProviderContract $provider): bool
    {
        return $this->guard()->check();
    }
}