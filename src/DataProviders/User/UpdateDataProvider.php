<?php

namespace CrCms\Passport\DataProviders\User;

use CrCms\Foundation\Transporters\AbstractValidateDataProvider;
use CrCms\Passport\Attributes\UserAttribute;
use CrCms\Passport\Models\ApplicationModel;
use Illuminate\Validation\Rule;

/**
 * Class UpdateDataProvider
 * @package CrCms\Passport\DataProviders\User
 */
class UpdateDataProvider extends AbstractValidateDataProvider
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'app_key' => ['required', Rule::exists((new ApplicationModel())->getTable(), 'app_key')],
            'mobile' => ['size:11', Rule::unique('passport_users')->ignore($this->route()->parameter('user'), 'id')],
            'password' => ['min:6'],
            'status' => ['required', 'integer', Rule::in(array_keys(UserAttribute::getStaticTransform(UserAttribute::KEY_STATUS)))]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'app_key' => '应用ID',
            'mobile' => '手机号',
            'password' => '密码',
            'status' => '状态'
        ];
    }
}