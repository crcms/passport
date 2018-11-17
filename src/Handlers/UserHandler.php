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
use CrCms\Microservice\Server\Exceptions\BadRequestException;
use CrCms\Microservice\Server\Exceptions\UnauthorizedException;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\Jwt\CheckTask;
use CrCms\Passport\Tasks\Jwt\ParserTask;

/**
 * Class UserHandler
 * @package CrCms\Passport\Handlers
 */
class UserHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return UserModel
     */
    public function handle(DataProviderContract $provider): UserModel
    {
        /* @var \Lcobucci\JWT\Token $token */
        try {
            $token = $this->app->make(ParserTask::class)->handle($provider->get('token'));
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }

        if (
            !$this->app->make(CheckTask::class)->handle($token) ||
            $token->isExpired()
        ) {
            throw new UnauthorizedException('unauthorized');
        }

        $userId = $token->getClaim('jti');
        return $this->app->make(UserRepository::class)->byIntIdOrFail($userId);
    }
}