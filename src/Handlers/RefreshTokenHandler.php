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
use CrCms\Passport\Exceptions\PassportException;
use CrCms\Passport\Tasks\Jwt\CheckTask;
use CrCms\Passport\Tasks\Jwt\CreateTask;
use CrCms\Passport\Tasks\Jwt\ParserTask;
use Lcobucci\JWT\Token;
use Exception;

/**
 * Class RefreshTokenHandler
 * @package CrCms\Passport\Handlers
 */
final class RefreshTokenHandler extends AbstractHandler
{
    /**
     * @param DataProviderContract $provider
     * @return array
     */
    public function handle(DataProviderContract $provider): array
    {
        try {
            /* @var Token $token */
            $token = $this->app->make(ParserTask::class)->handle($provider->get('token'));
        } catch (Exception $exception) {
            throw new PassportException($exception->getMessage());
        }

        if (!$this->app->make(CheckTask::class)->handle($token)) {
            throw new PassportException('Token error');
        }

        $tokens = $this->app->make(CreateTask::class)->handle(
            $token->getClaim('uid'),
            $token->getClaim('aud')
        );

        // 加入黑名单
        $this->cache->forever($token->getClaim('jti'), 1);

        return $tokens;
    }
}