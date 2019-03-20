<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-08-18 15:14
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Passport\Resources;

use CrCms\Microservice\Dispatching\AbstractTransformer;
use CrCms\Passport\Models\UserModel;

/**
 * Class UserResource
 * @package CrCms\Passport\Resources
 */
class UserResource extends AbstractTransformer
{
    /**
     * @param UserModel $model
     * @return array
     */
    public function toArray(UserModel $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'nickname' => $model->nickname,
            'email' => $model->email,
            'mobile' => $model->mobile,
            'status' => $model->status,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
