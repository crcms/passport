<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-15 06:27
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Tasks\Jwt\CheckTask;
use CrCms\Passport\Tasks\Jwt\ParserTask;
use Lcobucci\JWT\ValidationData;

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
        /* @var \Lcobucci\JWT\Token $token */
        try {
            $token = $this->app->make(ParserTask::class)->handle($provider->get('token'));
        } catch (\Exception $exception) {
            return false;
        }

        if (!$this->app->make(CheckTask::class)->handle($token)) {
            return false;
        }

        return !$token->isExpired();
    }
}