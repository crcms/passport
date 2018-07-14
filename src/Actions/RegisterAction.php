<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Foundation\App\Actions\ActionTrait;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterAction
 * @package CrCms\Passport\Actions
 */
class RegisterAction implements ActionContract
{
    use RegistersUsers, ValidatesRequests, ActionTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var Config
     */
    protected $config;

    /**
     * LoginAction constructor.
     * @param Request $request
     */
    public function __construct(Request $request, UserRepository $repository, Config $config)
    {
        $this->request = $request;
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * @param array $data
     * @return UserModel
     */
    public function handle(array $data = [])
    {
        $this->resolveDefaults($data);

        $this->validateRegister();

        $user = $this->repository->create(Arr::only($this->request->all(), $this->defaults['fields']));

        $this->registeredEvent($user);

        $this->guard()->login($user);

        return $user;
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

        return Arr::only($all, $this->defaults['fields']);
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
        return Auth::guard($this->defaults['guard']);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function resolveDefaults(array $data): void
    {
        $this->defaults['guard'] = $data['guard'] ?? $this->config->get('auth.defaults.guard');
        $this->defaults['fields'] = $data['fields'] ?? ['name', 'email', 'password'];
    }
}