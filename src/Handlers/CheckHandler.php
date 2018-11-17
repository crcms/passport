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
use CrCms\Microservice\Server\Exceptions\UnauthorizedException;
use CrCms\Passport\Tasks\Jwt\CheckTask;
use CrCms\Passport\Tasks\Jwt\ParserTask;

/**
 * Class CheckHandler
 * @package CrCms\Passport\Handlers
 */
final class CheckHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return bool
     */
    public function handle(DataProviderContract $provider): bool
    {
        /* @var \Lcobucci\JWT\Token $token */
        $token = $this->app->make(ParserTask::class)->handle($provider->get('token'));

        if (
            !$this->app->make(CheckTask::class)->handle($token) ||
            $token->isExpired()
        ) {
            throw new UnauthorizedException("unauthorized");
        }

        return true;
    }
}