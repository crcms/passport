<?php

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\App\Handlers\Traits\RequestHandlerTrait;
use CrCms\Passport\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Config\Repository as Config;

/**
 * Class JWTTokenHandler
 * @package CrCms\Passport\Handlers
 */
class JWTTokenHandler extends AbstractHandler
{
    use RequestHandlerTrait;

    /**
     * @var Config
     */
    protected $config;

    /**
     * JWTTokenHandler constructor.
     * @param Request $request
     * @param Config $config
     */
    public function __construct(Request $request, Config $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * @param mixed ...$params
     * @return array
     */
    public function handle(...$params): array
    {
        /* @var UserModel */
        $user = $params[0];

        /* @var array */
        $cookieToken = $params[1];

        $jwtToken = $this->guard()
            ->setTTL(Carbon::createFromTimestamp($cookieToken['expired_at'])->diffInMinutes(now()))
            ->fromUser($user->setJWTCustomClaims(['token' => $cookieToken['token'], 'app_key' => $this->request->input('application_key')]));

        return [
            'token' => $jwtToken,
            'token_type' => 'Bearer',
            'expired_at' => $cookieToken['expired_at']
        ];
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->config->get('auth.defaults.guard'));
    }
}