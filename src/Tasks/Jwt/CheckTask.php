<?php

namespace CrCms\Passport\Tasks\Jwt;

use CrCms\Foundation\Tasks\AbstractTask;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

/**
 * Class CheckTask
 * @package CrCms\Passport\Tasks\Jwt
 */
class CheckTask extends AbstractTask
{

    public function handle(...$params): bool
    {
        /* @var Token $token */
        $token = $params[0];
//        $ignoreExpired = $params[1] ?? ;
        dd($token);
        if ($this->cache->has('jwt_'))

        return $token->verify(new Sha256(), $this->config->get('passport.secret'));

//        if ($ignoreExpired) {

//        }
//        $token->isExpired()
//
////        $userId = $params[1];
////        $appKey = $params[2];
//
//        $data = $this->app->make(ValidationData::class);
////            ->setIssuer($this->config->get('passport.issuer'))//签发人
////            ->setAudience($appKey)
////            ->setId($userId);
//        dd($token->verify(new Sha256(), $this->config->get('passport.secret')));
//        return $token->validate($data);
//
//        $token = (new Parser())->parse((string) $token); // Parses from a string
//        $token->getHeaders(); // Retrieves the token header
//        $token->getClaims(); // Retrieves the token claims
//
//        echo $token->getHeader('jti'); // will print "4f1g23a12aa"
//        echo $token->getClaim('iss'); // will print "http://example.com"
//        echo $token->getClaim('uid'); // will print "1"
//
//
//        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
//        $data->setIssuer('http://example.com');
//        $data->setAudience('http://example.org');
//        $data->setId('4f1g23a12aa');
//
//        var_dump($token->validate($data)); // false, because we created a token that cannot be used before of `time() + 60`
//
//        $data->setCurrentTime(time() + 60); // changing the validation time to future
//
//        var_dump($token->validate($data)); // true, because validation information is equals to data contained on the token
//
//        $data->setCurrentTime(time() + 4000); // changing the validation time to future
//
//        var_dump($token->validate($data)); // false, because token is expired since current time is greater than exp
    }
}