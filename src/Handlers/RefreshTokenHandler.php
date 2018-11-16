<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 17:51
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Microservice\Server\Exceptions\BadRequestException;
use CrCms\Passport\Tasks\Jwt\CheckTask;
use CrCms\Passport\Tasks\Jwt\CreateTask;
use CrCms\Passport\Tasks\Jwt\ParserTask;
use Exception;

/**
 * Class RefreshTokenHandler
 * @package CrCms\Passport\Handlers
 */
class RefreshTokenHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return array
     */
    public function handle(DataProviderContract $provider): array
    {
        try {
            $token = $this->app->make(ParserTask::class)->handle($provider->get('token'));
        } catch (Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }

        if (!$this->app->make(CheckTask::class)->handle($token)) {
            throw new BadRequestException('Token error');
        }

        return $this->app->make(CreateTask::class)->handle(
            $token->getClaim('jti'),
            $token->getClaim('aud')
        );
    }
}