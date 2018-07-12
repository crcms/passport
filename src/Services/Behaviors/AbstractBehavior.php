<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018/7/6 6:20
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Services\Behaviors;

use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\UserBehaviorModel;
use CrCms\Passport\Models\UserModel;
use CrCms\Passport\Repositories\UserBehaviorRepository;
use Illuminate\Http\Request;

/**
 * Class AbstractBehavior
 * @package CrCms\Passport\Services\Behaviors
 */
abstract class AbstractBehavior
{
    /**
     * @var UserBehaviorRepository
     */
    protected $userBehaviorRepository;

    /**
     * @var
     */
    protected $userBehavior;

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * AbstractBehavior constructor.
     * @param UserModel $userModel
     * @param Request|null $request
     * @param array $data
     */
    public function __construct(UserModel $userModel, ?Request $request = null, array $data = [])
    {
        $this->user = $userModel;
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * @return UserBehaviorModel
     */
    public function getUserBehavior(): UserBehaviorModel
    {
        return $this->userBehavior;
    }

    /**
     * @param UserBehaviorModel $userBehaviorModel
     */
    protected function setUserBehavior(UserBehaviorModel $userBehaviorModel)
    {
        $this->userBehavior = $userBehaviorModel;
    }

    /**
     * @return UserBehaviorRepository
     */
    protected function userBehaviorRepository(): UserBehaviorRepository
    {
        if (!$this->userBehaviorRepository instanceof UserBehaviorRepository) {
            $this->userBehaviorRepository = app(UserBehaviorRepository::class);
        }

        return $this->userBehaviorRepository;
    }

    /**
     * @param int $id
     * @param array $data
     * @return UserBehaviorModel
     */
    protected function update(int $id, array $data = []): UserBehaviorModel
    {
        return $this->userBehaviorRepository()->update([
            'status' => UserAttribute::AUTH_STATUS_SUCCESS
        ], $id);
    }
}