<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:04
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Repository\AbstractRepository;
use CrCms\Passport\Models\UserModel;

/**
 * Class UserRepository
 * @package CrCms\Passport\Repositories
 */
class UserRepository extends AbstractRepository
{
    /**
     * @var array
     */
    protected $guard = ['name', 'email', 'password', 'mobile', 'app_id'];

    /**
     * @return UserModel
     */
    public function newModel(): UserModel
    {
        return app(UserModel::class);
    }

    /**
     * @param string $value
     * @return UserModel
     */
    public function byNameOrMobileOrEmailOrFail(string $value)
    {
        return $this->where('name', $value)->orWhere('email', $value)->orWhere('mobile', $value)->firstOrFail();
    }

    /**
     * @param UserModel $user
     * @param array $data
     * @return UserModel
     */
    public function storeLoginInfo(UserModel $user, array $data): UserModel
    {
        return $this->setGuard(['ticket', 'ticket_expired_at'])->update($data, $user->id);
    }
}