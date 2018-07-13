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
        $this->validate($this->request, $this->request->all());

        $user = $this->repository->create(Arr::only($this->request->all(), $collects ? $collects->get('field') : []));

        $this->registeredEvent($user);

        $this->guard()->login($user);

        return $user;
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
}