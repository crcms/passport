<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-04 21:04
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Repositories;

use CrCms\Foundation\App\Repositories\AbstractRepository;
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
    protected $guard = ['name', 'email', 'password', 'tel'];

    /**
     * @return UserModel
     */
    public function newModel(): UserModel
    {
        return app(UserModel::class);
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

//
//    /**
//     * @param UserModel $userModel
//     * @return array
//     */
//    public function getTokenInfoByUser(UserModel $userModel): array
//    {
//        $token = Auth::guard()->fromUser($userModel);
//
//        return [
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//            'expires_in' => Auth::guard()->factory()->getTTL() * 60
//        ];
//    }
//
//    /**
//     * @param UserModel $userModel
//     * @return \Illuminate\Database\Eloquent\Model|null|object|static
//     */
//    public function getRegisterInfo(UserModel $userModel)
//    {
//        return $userModel->hasManyAuthInfo()
//            ->where('type', UserAttribute::AUTH_TYPE_REGISTER)->first();
//    }
//
//    /**
//     * @param UserModel $userModel
//     * @return Collection
//     */
//    public function getLoginInfo(UserModel $userModel): Collection
//    {
//        return $userModel->hasManyAuthInfo()
//            ->where('type', UserAttribute::AUTH_TYPE_LOGIN)->orderBy('created_at', 'desc')->get();
//    }
}