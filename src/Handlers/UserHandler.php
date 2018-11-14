<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 16:39
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Models\UserModel;

/**
 * Class UserHandler
 * @package CrCms\Passport\Handlers
 */
class UserHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return UserModel
     */
    public function handle(DataProviderContract $provider): UserModel
    {
        return $this->guard()->user();
    }
}