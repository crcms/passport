<?php

namespace CrCms\Passport\Tasks\Jwt;

use CrCms\Foundation\Tasks\AbstractTask;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

/**
 * Class ParserTask
 * @package CrCms\Passport\Tasks\Jwt
 */
class ParserTask extends AbstractTask
{
    /**
     * @param mixed ...$params
     * @return Token
     */
    public function handle(...$params): Token
    {
        /* @var string $token */
        $token = $params[0];
        return $this->app->make(Parser::class)->parse($token);
    }
}