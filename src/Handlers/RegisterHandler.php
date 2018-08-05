<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Handlers;

use CrCms\Foundation\App\Handlers\AbstractHandler;
use CrCms\Foundation\App\Handlers\Traits\RepositoryHandlerTrait;
use CrCms\Foundation\App\Handlers\Traits\RequestHandlerTrait;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class RegisterHandler extends AbstractHandler
{
    use RegistersUsers, RequestHandlerTrait, RepositoryHandlerTrait;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $defaultFields = ['name', 'email', 'password'];

    /**
     * @var LoginHandler
     */
    protected $login;

    /**
     * LoginAction constructor.
     * @param Request $request
     */
    public function __construct(Request $request, UserRepository $repository, Config $config, LoginHandler $login)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->config = $config;
        $this->login = $login;
    }

    /**
     * @return UserModel
     */
    public function handle(): UserModel
    {
        $this->validateRegister();

        $user = $this->repository->create(Arr::only($this->request->all(), $this->defaultFields));

        $this->registeredEvent($user);

        return $this->login->handle();
        //$this->guard()->login($user);
        //return $user;
    }

    /**
     *
     */
    protected function validateRegister(): void
    {
        $this->validate($this->request, $this->validateRules());
    }

    /**
     * @return array
     */
    protected function validateRules(): array
    {
        $all = [
            'name' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6',
        ];

        return Arr::only($all, $this->defaultFields);
    }

    /**
     * @param UserModel $user
     * @return void
     */
    protected function registeredEvent(UserModel $user): void
    {
        event(new RegisteredEvent(
            $user,
            UserAttribute::AUTH_TYPE_REGISTER,
            [
                'ip' => $this->request->ip(),
                'agent' => $this->request->userAgent(),
            ]
        ));
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->config->get('auth.defaults.guard'));
    }
}