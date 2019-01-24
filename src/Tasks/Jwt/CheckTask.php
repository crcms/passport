<?php

namespace CrCms\Passport\Tasks\Jwt;

use CrCms\Foundation\Tasks\AbstractTask;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;

/**
 * Class CheckTask
 * @package CrCms\Passport\Tasks\Jwt
 */
class CheckTask extends AbstractTask
{
    /**
     * @param mixed ...$params
     * @return bool
     */
    public function handle(...$params): bool
    {
        /* @var Token $token */
        $token = $params[0];

        if ($this->cache->has($token->getClaim('jti'))) {
            return false;
        }

        return $token->verify(new Sha256(), $this->config->get('passport.secret'));
    }
}