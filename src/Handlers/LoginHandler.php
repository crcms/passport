<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\Handlers\AbstractHandler;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Microservice\Server\Exceptions\UnprocessableEntityException;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\LoginEvent;
use CrCms\Passport\Handlers\Traits\Token;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\ApplicationRepository;
//use Illuminate\Foundation\Auth\ThrottlesLogins;
use CrCms\Passport\Repositories\UserRepository;
use CrCms\Passport\Tasks\Jwt\CreateTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Class LoginHandler
 * @package CrCms\Passport\Actions
 */
class LoginHandler extends AbstractHandler
{
    use Token;

    /**
     * @param DataProviderContract $provider
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(DataProviderContract $provider): array
    {
        $user = $this->app->make(UserRepository::class)->byNameOrMobileOrEmailOrFail($provider->get('name'));

        if (!Hash::check($provider->get('password'), $user->password)) {
            throw new UnprocessableEntityException('name or password error');
        }

        $appKey = $provider->get('app_key');

        return $this->app->make(CreateTask::class)->handle($user->id, $appKey);
    }

    protected function x()
    {
        return $this->app->make(Builder::class)
            ->setIssuer('http://example.com')//签发人 Configures the issuer (iss claim)
            ->setAudience('http://example.org')//受众 Configures the audience (aud claim)
            ->setId('4f1g23a12aa', true)//JTI编号 Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())//签发时间 Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 60)//生效时间 Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600)//过期时间 Configures the expiration time of the token (exp claim)
            ->set('uid', 1)// Configures a new claim, called "uid"
            ->sign(new Sha256(), 'testing')// creates a signature using "testing" as key
            ->getToken(); // Retrieves the generated token
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return array_first(array_except(array_keys($this->config->get('passport.login_rules')), 'password'));
    }

    /**
     * @return mixed
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), true
        );
    }

    /**
     * @throws ValidationException
     */
    protected function throwLoginError()
    {
        throw ValidationException::withMessages([
            'failed' => trans('passport::auth.failed'),
        ])->status(401);
    }

    /**
     * @param Request $request
     * @param UserModel $user
     */
    protected function authenticatedEvent(Request $request, UserModel $user)
    {
        event(new LoginEvent(
            $user,
            UserAttribute::AUTH_TYPE_LOGIN,
            ['ip' => $request->ip(), 'agent' => $request->userAgent(), '_redirect' => $request->input('_redirect', '')]
        ));
    }

    /**
     * @throws ValidationException
     */
    protected function throwLockout(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'locked' => [Lang::get('passport::auth.throttle', ['seconds' => $seconds])],
        ])->status(423);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        /* @todo 这里要加上域判断，暂时只支持当前APP */
        $app = $this->app->make(ApplicationRepository::class)->byAppKeyOrFail($request->input('app_key'));
        return array_merge(array_only($request->all(), array_keys($this->config->get('passport.login_rules'))), ['app_id' => $app->id]);
    }

    /**
     * @param Request $request
     * @param string $appKey
     * @param UserModel $user
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function authenticated(Request $request, string $appKey, UserModel $user): array
    {
        //event
        //$this->authenticatedEvent($request, $user);

        //token
        $tokens = $this->token()->new($this->application($appKey), $user);
        return [
            'jwt' => $this->jwt($this->jwtToken($appKey, $user, $tokens), $tokens['expired_at']),
            'cookie' => $this->cookie($tokens['token'], $tokens['expired_at'])
        ];
    }
}