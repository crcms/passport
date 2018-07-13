<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-13 11:36
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Actions;

use CrCms\Foundation\App\Actions\ActionContract;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Events\RegisteredEvent;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterAction
 * @package CrCms\Passport\Actions
 */
class RegisterAction implements ActionContract
{
    use RegistersUsers, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $guard;

    /**
     * LoginAction constructor.
     * @param Request $request
     */
    public function __construct(Request $request, UserRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    /**
     * @param Collection|null $collects
     * @return bool|mixed
     */
    public function handle(?Collection $collects = null)
    {
        $this->guard = $collects ? $collects->get('guard', 'api') : null;
        $fields = $collects ? $collects->get('fields', ['name', 'email', 'password']) : [];

        $this->validateRegister($fields);

        $user = $this->repository->create($this->request->all());

        $this->registeredEvent($user);

        $this->guard()->login($user);

        return $user;
    }

    /**
     * @param array $fields
     */
    protected function validateRegister(array $fields = [])
    {
        $this->validate($this->request, $this->validateRules($fields));
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function validateRules(array $fields = [])
    {
        $all = [
            'name' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6',
        ];

        return Arr::only($all, $fields ? $fields : $all);
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
        return Auth::guard($this->guard);
    }
}