<?php

namespace CrCms\Passport\Tasks\Jwt;

use CrCms\Foundation\Tasks\AbstractTask;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Class CreateTask
 * @package CrCms\Passport\Tasks\Jwt
 */
class CreateTask extends AbstractTask
{
    /**
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        $userId = $params[0];
        $appKey = $params[1];
        $expired = $this->expired();

        return $this->jwt(
            strval($this->createToken($userId, $appKey, $expired)),
            $expired
        );
    }

    /**
     * @param int $id
     * @param int $appKey
     * @param int $expired
     * @return mixed
     */
    protected function createToken(int $id, int $appKey, int $expired)
    {
        return $this->app->make(Builder::class)
            ->setIssuer($this->config->get('passport.issuer'))//签发人 Configures the issuer (iss claim)
            ->setAudience($appKey)//受众 Configures the audience (aud claim)
            ->setId(uniqid(), true)//JTI编号 Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())//签发时间 Configures the time that the token was issue (iat claim)
            ->setNotBefore(time())//生效时间 Configures the time that the token can be used (nbf claim)
            ->setExpiration($expired)//过期时间 Configures the expiration time of the token (exp claim)
            ->set('uid', $id)// Configures a new claim, called "uid"
            ->sign(new Sha256(), $this->config->get('passport.secret'))// creates a signature using "testing" as key
            ->getToken(); // Retrieves the generated token
    }

    /**
     * @param string $token
     * @param int $expired
     * @return array
     */
    protected function jwt(string $token, int $expired): array
    {
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'expired' => $expired
        ];
    }

    /**
     * @return int
     */
    protected function expired(): int
    {
        return time() + $this->config->get('passport.ttl', 10) * 60;
    }
}